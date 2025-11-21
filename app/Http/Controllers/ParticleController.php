<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParticleController extends Controller
{
    public function flashFirmware()
    {
        $deviceId = "29002b000b47313037363132";
        $token = "dce02706ae95dc0dc7d5243f08e79788efda7f42";

        $firmware = storage_path('app/public/firmware.bin');

        if (!file_exists($firmware)) {
            return response()->json(["error" => "firmware.bin no encontrado"], 404);
        }

        $response = Http::attach(
            'file',
            file_get_contents($firmware),
            'firmware.bin'
        )->post("https://api.particle.io/v1/devices/$deviceId", [
            'access_token' => $token
        ]);

        return $response->json();
    }
}