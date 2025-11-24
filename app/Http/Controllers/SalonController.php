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
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:50',
            'device_id' => 'required|string|max:255',
        ]);

        // Crear salón
        $salon = new Salon();
        $salon->nombre = $validated['nombre'];
        $salon->ubicacion = $validated['ubicacion'];
        $salon->save();

        // Crear dispositivo Particle relacionado
        $dispositivo = new DispositivoParticle();
        $dispositivo->id_salon = $salon->id_salon;
        $dispositivo->device_id = $validated['device_id'];
        $dispositivo->nombre = 'Principal';
        $dispositivo->save();

        // Crear sensores (Temperatura y Humedad)
        $sensores = [
            ['tipo' => 'temperatura', 'tipo' => 'temperatura'],
            ['tipo' => 'humedad', 'tipo' => 'humedad']
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
        $dispositivo->update(['salon_id' => $salon->id]);

        return back()->with('success', 'Dispositivo reasignado correctamente al salón.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'particle_id' => 'nullable|string|max:255'
        ]);

        $salon = Salon::findOrFail($id);

        // Buscar el dispositivo asociado (si existe)
        $dispositivo = $salon->dispositivos()->first();

        // Actualizar el salón
        $salon->update([
            'nombre' => $request->nombre,
            'ubicacion' => $request->ubicacion,
        ]);

        // Si hay dispositivo, actualizamos su device_id
        if ($dispositivo) {
            $dispositivo->update(['device_id' => $request->particle_id]);
        }

        return redirect()->route('tabla')->with('success', 'Salón actualizado correctamente.');
    }
}