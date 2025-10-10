<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Usuario;
use App\Models\Salon;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LecturaController extends Controller
{
    public function index()
    {
        $valorMaximo = Lectura::max('valor');
        $totalSalones = Salon::count();
        $totalAlumnos = Usuario::where('tipo_usuario', 'alumno')->count();
        $salones = Salon::with(['sensores.lecturas' => function ($q) {
            $q->latest('fecha_hora')->limit(1);
        }])->get();
        $reportes = Reporte::latest('created_at')->take(5)->get();
        return view('dashboard.dashboard', compact('valorMaximo', 'totalSalones', 'totalAlumnos', 'salones', 'reportes'));
    }

    public function salones()
    {
        $salones = Salon::with(['sensores.lecturas' => function ($query) {
            $query->latest('fecha_hora')->limit(1);
        }])->get();
        return view('salones.salones', compact('salones'));
    }

    /**
     * Este es el método que guarda los datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'valor' => 'required|numeric',
            'id_sensor' => 'required|integer|exists:sensores,id_sensor',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lectura = Lectura::create([
            'valor' => $request->input('valor'),
            'id_sensor' => $request->input('id_sensor'),
            'fecha_hora' => now(),
        ]);

        return response()->json([
            'message' => '¡Lectura guardada exitosamente!',
            'data' => $lectura
        ], 201);
    }
}