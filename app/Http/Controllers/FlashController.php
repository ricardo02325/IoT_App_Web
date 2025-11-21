<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlashController extends Controller
{
    public function flash(Request $request)
    {
        $deviceId = "29002b000b47313037363132";
        $token = "dce02706ae95dc0dc7d5243f08e79788efda7f42";       
        $firmwarePath = storage_path('app/public/firmware.bin');

        // Verifica que exista el firmware
        if (!file_exists($firmwarePath)) {
            return response()->json(["error" => "Firmware no encontrado"], 404);
        }

        $response = Http::attach(
            'file',
            file_get_contents($firmwarePath),
            'firmware.bin'
        )->post("https://api.particle.io/v1/devices/$deviceId", [
            'access_token' => $token
        ]);

        return response()->json($response->json());
    }
}