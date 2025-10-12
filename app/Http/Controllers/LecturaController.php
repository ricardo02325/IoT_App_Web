<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Usuario;
use App\Models\Salon;
use App\Models\Reporte;
use App\Models\Sensor; // ✅ Importar el modelo Sensor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LecturaController extends Controller
{
    public function index()
    {

        $valorMaximo = DB::table('lecturas')
        ->join('sensores', 'lecturas.id_sensor', '=', 'sensores.id_sensor')
        ->where('sensores.tipo', 'temperatura')
        ->max('lecturas.valor');
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
     * Guarda los datos enviados desde el cliente.
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

    /**
     * Genera y guarda lecturas simuladas para todos los sensores.
     */
    public function simular()
    {
        $sensores = Sensor::all();
        $lecturasGuardadas = [];

        if ($sensores->isEmpty()) {
            return response()->json(['message' => 'No hay sensores registrados.'], 404);
        }

        foreach ($sensores as $sensor) {
            // Generar valor simulado según tipo de sensor
            $valor = match ($sensor->tipo) {
                'temperatura' => rand(180, 300) / 10, // 18.0 - 30.0 °C
                'humedad' => rand(300, 800) / 10,     // 30% - 80%
                'luminosidad' => rand(0, 1000),       // 0 - 1000 lux
                default => rand(0, 100),
            };

            // Guardar la lectura simulada
            $lectura = Lectura::create([
                'id_sensor' => $sensor->id_sensor,
                'valor' => $valor,
                'fecha_hora' => now(),
            ]);

            $lecturasGuardadas[] = $lectura;
        }

        return response()->json([
            'message' => '✅ Lecturas simuladas guardadas exitosamente.',
            'data' => $lecturasGuardadas
        ], 201);
    }
}