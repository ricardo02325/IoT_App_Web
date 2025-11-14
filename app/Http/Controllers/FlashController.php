<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlashController extends Controller
{
    public function flash(Request $request)
    {
        // TU DEVICE ID Y TOKEN
        $device = "25001d000847313037363132";
        $token  = "88a24c4118cb7a06a968dcbb29c748133fad78ef";

        // Ruta absoluta al firmware dentro de Laravel
        $path = storage_path("app/public/firmware.bin");

        if (!file_exists($path)) {
            return response()->json([
                "error" => "No se encuentra el archivo firmware.bin"
            ], 404);
        }

        // Petición a API Particle
        $response = Http::attach(
            'file',
            file_get_contents($path),
            'firmware.bin'
        )->post("https://api.particle.io/v1/devices/$device", [
            'access_token' => $token,
        ]);

        return $response->json();
    }
}