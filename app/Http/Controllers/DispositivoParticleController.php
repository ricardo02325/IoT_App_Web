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
}