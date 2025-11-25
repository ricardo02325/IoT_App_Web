<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\DispositivoParticle;
use App\Models\Sensor;

class SalonController extends Controller
{
    /**
     * Mostrar todos los salones con su dispositivo Particle (si lo tienen)
     */
    public function index()
    {
        // Obtener salones junto con su dispositivo y sensores
        $salones = Salon::with(['dispositivoParticle.sensores.lecturas'])->get();

        // Obtener dispositivos Particle que aún no estén asignados a ningún salón
        $dispositivos = DispositivoParticle::whereNull('salon_id')->get();

        return view('salones.salones', compact('salones', 'dispositivos'));
    }

    /**
     * Mostrar un salón específico con su dispositivo y sensores
     */
    public function show($id)
    {
        $salon = Salon::with(['dispositivoParticle.sensores.lecturas'])->findOrFail($id);

        return view('salones.show', compact('salon'));
    }

    /**
     * Crear un nuevo salón y asignarle un dispositivo Particle
     */
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            // CORRECCIÓN: Agregamos 'unique:salones,nombre' para evitar duplicados
            'nombre' => 'required|string|max:255|unique:salones,nombre',
            'ubicacion' => 'required|string|max:50',
            'device_id' => 'required|string|max:255',
        ]);

        // Crear salón
        $salon = new Salon();
        $salon->nombre = $validated['nombre'];
        $salon->ubicacion = $validated['ubicacion'];
        // $salon->capacidad = 30; // Descomenta si tu BD requiere este campo
        $salon->save(); 

        // Crear dispositivo Particle relacionado
        $dispositivo = new DispositivoParticle();
        $dispositivo->id_salon = $salon->id_salon; // Usamos el ID correcto
        $dispositivo->device_id = $validated['device_id'];
        $dispositivo->nombre = 'Principal';
        $dispositivo->save();

        // Array de sensores limpio
        $sensores = [
            ['tipo' => 'temperatura'],
            ['tipo' => 'humedad']
        ];

        foreach ($sensores as $s) {
            $sensor = new Sensor();
            $sensor->id_salon = $salon->id_salon;
            $sensor->tipo = $s['tipo'];
            $sensor->save();
        }

        return redirect()->route('tabla')->with('success', 'Salón creado con sensores');
    }

    /**
     * Permitir cambiar un dispositivo Particle de salón
     */
    public function asignarDispositivo(Request $request, $idSalon)
    {
        $request->validate([
            'device_id' => 'required|exists:dispositivos_particle,device_id',
        ]);

        $salon = Salon::findOrFail($idSalon);
        $dispositivo = DispositivoParticle::where('device_id', $request->device_id)->firstOrFail();

        // Liberar el dispositivo anterior (si lo había)
        if ($salon->dispositivoParticle) {
            $salon->dispositivoParticle->update(['salon_id' => null]);
        }

        // Asignar el nuevo
        $dispositivo->update(['salon_id' => $salon->id_salon]); // Aseguramos usar id_salon

        return back()->with('success', 'Dispositivo reasignado correctamente al salón.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            // CORRECCIÓN: Validamos unique pero ignoramos el ID actual para poder editar sin errores
            'nombre' => 'required|string|max:255|unique:salones,nombre,' . $id . ',id_salon',
            'ubicacion' => 'required|string|max:255',
            'particle_id' => 'nullable|string|max:255'
        ]);

        $salon = Salon::findOrFail($id);

        // Buscar el dispositivo asociado (si existe) a través de la relación 1:1
        $dispositivo = $salon->dispositivoParticle; 

        // Actualizar el salón
        $salon->update([
            'nombre' => $request->nombre,
            'ubicacion' => $request->ubicacion,
        ]);

        // Si hay dispositivo, actualizamos su device_id
        if ($dispositivo) {
            $dispositivo->update(['device_id' => $request->particle_id]);
        } elseif ($request->particle_id) {
            // Si no tenía dispositivo pero se le asignó uno nuevo al editar
            $nuevoDispositivo = new DispositivoParticle();
            $nuevoDispositivo->id_salon = $salon->id_salon;
            $nuevoDispositivo->device_id = $request->particle_id;
            $nuevoDispositivo->nombre = 'Principal';
            $nuevoDispositivo->save();
        }

        return redirect()->route('tabla')->with('success', 'Salón actualizado correctamente.');
    }

    /**
     * Eliminar un salón y sus relaciones
     */
    public function destroy($id)
    {
        try {
            $salon = Salon::findOrFail($id);

            // Eliminar en cascada: primero los sensores y lecturas, luego el dispositivo, finalmente el salón
            if ($salon->dispositivoParticle) {
                // Eliminar sensores y sus lecturas
                foreach ($salon->dispositivoParticle->sensores as $sensor) {
                    $sensor->lecturas()->delete(); // Eliminar lecturas del sensor
                    $sensor->delete(); // Eliminar sensor
                }
                // Eliminar dispositivo Particle
                $salon->dispositivoParticle->delete();
            }

            // Eliminar el salón
            $salon->delete();

            return redirect()->route('tabla')->with('success', 'Salón eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('tabla')->with('error', 'Error al eliminar el salón: ' . $e->getMessage());
        }
    }
}