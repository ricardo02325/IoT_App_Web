<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DispositivoParticle;

class DispositivoParticleController extends Controller
{
    public function getSensores($device_id)
    {
        $dispositivo = DispositivoParticle::with('sensores')->where('device_id', $device_id)->first();

        if (!$dispositivo) {
            return response()->json(['message' => 'Dispositivo no encontrado'], 404);
        }

        return response()->json([
            'device_id' => $dispositivo->device_id,
            'sensores' => $dispositivo->sensores // aquí cada sensor tendrá su id y tipo
        ]);
    }
    public function obtenerSalonPorDeviceID($deviceId)
    {
        $dispositivo = \DB::table('dispositivos_particle')
            ->where('device_id', $deviceId)
            ->first();

        if (!$dispositivo) {
            return response()->json([
                'error' => 'Dispositivo no encontrado'
            ], 404);
        }

        $salon = \DB::table('salones')
            ->where('id_salon', $dispositivo->id_salon)
            ->first();

        return response()->json([
            'id_salon' => $dispositivo->id_salon,
            'nombre_salon' => $salon?->nombre
        ]);
    }
}