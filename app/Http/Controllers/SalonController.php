<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\DispositivoParticle;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class SalonController extends Controller
{
    /**
     * Mostrar todos los salones con su dispositivo Particle (si lo tienen)
     */
    public function index()
    {
        // Obtener salones junto con su dispositivo y sensores
        $salones = Salon::with(['dispositivoParticle.sensores.lecturas'])->get();

        return view('salones.salones', compact('salones'));
    }

    /**
     * Crear un nuevo salón y asignarle un dispositivo Particle
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validación mejorada
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'ubicacion' => 'required|string|max:50',
                'device_id' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('dispositivos_particle', 'device_id')
                ],
            ], [
                'nombre.required' => 'El nombre del salón es obligatorio.',
                'ubicacion.required' => 'La ubicación es obligatoria.',
                'device_id.required' => 'El ID del dispositivo Particle es obligatorio.',
                'device_id.unique' => 'El ID del dispositivo Particle ya está registrado en el sistema.',
            ]);

            // Crear salón
            $salon = Salon::create([
                'nombre' => $validated['nombre'],
                'ubicacion' => $validated['ubicacion'],
            ]);

            // Crear dispositivo Particle relacionado
            DispositivoParticle::create([
                'id_salon' => $salon->id_salon,
                'device_id' => $validated['device_id'],
                'nombre' => 'Principal',
            ]);

            DB::commit();

            return redirect()->route('tabla')->with('success', 'Salón creado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_duplicate_modal', $e->validator->errors()->has('device_id'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el salón: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar un salón existente
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $salon = Salon::findOrFail($id);
            $dispositivo = $salon->dispositivoParticle;

            // Validación mejorada para la actualización
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'ubicacion' => 'required|string|max:255',
                'particle_id' => [
                    'nullable', 
                    'string', 
                    'max:255',
                    Rule::unique('dispositivos_particle', 'device_id')->ignore($dispositivo?->id_dispositivo, 'id_dispositivo')
                ]
            ], [
                'nombre.required' => 'El nombre del salón es obligatorio.',
                'ubicacion.required' => 'La ubicación es obligatoria.',
                'particle_id.unique' => 'El ID del dispositivo Particle ya está registrado en otro salón.',
            ]);

            // Actualizar el salón
            $salon->update([
                'nombre' => $validated['nombre'],
                'ubicacion' => $validated['ubicacion'],
            ]);

            // Manejar el dispositivo Particle
            if (!empty($validated['particle_id'])) {
                if ($dispositivo) {
                    $dispositivo->update(['device_id' => $validated['particle_id']]);
                } else {
                    DispositivoParticle::create([
                        'id_salon' => $salon->id_salon,
                        'device_id' => $validated['particle_id'],
                        'nombre' => 'Principal',
                    ]);
                }
            } else if ($dispositivo) {
                $dispositivo->delete();
            }

            DB::commit();

            return redirect()->route('tabla')->with('success', 'Salón actualizado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('show_duplicate_modal', $e->validator->errors()->has('particle_id'));
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el salón: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un salón y sus relaciones
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $salon = Salon::findOrFail($id);

            // Eliminar el dispositivo Particle asociado (y sus sensores/lecturas mediante cascada si está configurado)
            if ($salon->dispositivoParticle) {
                $salon->dispositivoParticle->delete();
            }

            // Eliminar el salón
            $salon->delete();

            DB::commit();

            return redirect()->route('tabla')->with('success', 'Salón eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tabla')->with('error', 'No se pudo eliminar el salón: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si un device_id ya existe (para AJAX)
     */
    public function checkDeviceId(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        $exists = DispositivoParticle::where('device_id', $request->device_id)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'El ID del dispositivo ya está registrado.' : 'ID disponible.'
        ]);
    }
}