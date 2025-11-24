<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Usuario;
use App\Models\Salon;
use App\Models\Reporte;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\DispositivoParticle;

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

        // ⚡ Corregido: traer dispositivo y sus sensores
        $salones = Salon::with([
            'dispositivoParticle.sensores.lecturas' => function ($q) {
                $q->latest('fecha_hora')->limit(1);
            }
        ])->get();

        $reportes = Reporte::latest('created_at')->take(5)->get();

        return view('dashboard.dashboard', compact('valorMaximo', 'totalSalones', 'totalAlumnos', 'salones', 'reportes'));
    }

    public function salones()
    {
        // ⚡ Cargar salones con dispositivos Particle y sensores con la última lectura
        $salones = Salon::with([
            'dispositivoParticle.sensores.lecturas' => function ($query) {
                $query->latest('fecha_hora')->limit(1);
            }
        ])->get();

        $sensores = [];
        foreach ($salones as $salon) {
            if ($salon->dispositivoParticle) {
                foreach ($salon->dispositivoParticle->sensores as $sensor) {
                    $sensores[] = [
                        'id_sensor' => $sensor->id_sensor,
                        'tipo' => $sensor->tipo,
                        'id_salon' => $salon->id_salon,
                        'ultima_lectura' => $sensor->lecturas->first()?->valor, // puede ser null
                        'fecha_lectura' => $sensor->lecturas->first()?->fecha_hora
                    ];
                }
            }
        }
        $dispositivosParticle = DispositivoParticle::whereIn('id_salon', $salones->pluck('id_salon'))->get();

        return view('salones.salones', compact('salones', 'sensores', 'dispositivosParticle'));
    }


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

    public function simular()
    {
        $sensores = Sensor::with('salon')->get();  // Cargar salón del sensor
        $lecturasGuardadas = [];

        if ($sensores->isEmpty()) {
            return response()->json(['message' => 'No hay sensores registrados.'], 404);
        }

        foreach ($sensores as $sensor) {

            // --- NO simular para salones con lecturas reales ---
            if ($sensor->salon && in_array($sensor->salon->ubicacion, ['5D', 'LIC'])) {
                continue;  // Saltar este sensor
            }

            // --- GENERAR VALOR SIMULADO ---
            $valor = match ($sensor->tipo) {
                'temperatura' => rand(180, 300) / 10,
                'humedad' => rand(300, 800) / 10,
                default => rand(0, 100),
            };

            // --- GUARDAR LECTURA ---
            $lectura = Lectura::create([
                'id_sensor' => $sensor->id_sensor,
                'valor' => $valor,
                'fecha_hora' => now(),
                'id_salon' => $sensor->id_salon ?? null
            ]);

            $lecturasGuardadas[] = $lectura;
        }

        return response()->json([
            'message' => '✅ Lecturas simuladas guardadas exitosamente.',
            'data' => $lecturasGuardadas
        ], 201);
    }

    public function graficas()
    {
        $temperatura = DB::table('lecturas')
            ->join('sensores', 'lecturas.id_sensor', '=', 'sensores.id_sensor')
            ->where('sensores.tipo', 'temperatura')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->pluck('lecturas.valor')
            ->reverse()
            ->values();

        $humedad = DB::table('lecturas')
            ->join('sensores', 'lecturas.id_sensor', '=', 'sensores.id_sensor')
            ->where('sensores.tipo', 'humedad')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->pluck('lecturas.valor')
            ->reverse()
            ->values();

        $luminosidad = DB::table('lecturas')
            ->join('sensores', 'lecturas.id_sensor', '=', 'sensores.id_sensor')
            ->where('sensores.tipo', 'luminosidad')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->pluck('lecturas.valor')
            ->reverse()
            ->values();

        $fechas = DB::table('lecturas')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->pluck('fecha_hora')
            ->map(fn($f) => \Carbon\Carbon::parse($f)->format('H:i'))
            ->reverse()
            ->values();

        $promedios = [
            'temperatura' => round($temperatura->avg(), 1),
            'humedad' => round($humedad->avg(), 1),
            'luminosidad' => round($luminosidad->avg(), 1)
        ];

        return view('salones.graficas', compact('fechas', 'temperatura', 'humedad', 'luminosidad', 'promedios'));
    }

    public function tabla()
    {
        // ⚡ Corregido: traer salones con dispositivo y sensores
        $salones = Salon::with('dispositivoParticle.sensores.lecturas')->get();
        $dispositivos = DispositivoParticle::all();

        return view('salones.tabla', compact('salones', 'dispositivos'));
    }
}