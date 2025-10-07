<?php

namespace App\Http\Controllers;

use App\Models\Lectura;   // ← Esto faltaba
use App\Models\Usuario;
use App\Models\Salon;
use App\Models\Reporte;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    /**
     * Método principal (para la ruta '/')
     */
    public function index()
    {
        // Valor máximo registrado en todas las lecturas
        $valorMaximo = Lectura::max('valor');

        // Total de salones
        $totalSalones = Salon::count();

        // Total de alumnos
        $totalAlumnos = Usuario::where('tipo_usuario', 'alumno')->count();

        // Salones con sensores y últimas lecturas
        $salones = Salon::with([
            'sensores.lecturas' => function ($q) {
                $q->latest('fecha_hora')->limit(1);
            }
        ])->get();

        // Traer reportes recientes (ejemplo: últimos 5)
        $reportes = Reporte::latest('created_at')->take(5)->get();

        return view('dashboard.dashboard', compact('valorMaximo', 'totalSalones', 'totalAlumnos', 'salones', 'reportes'));
    }

    /**
     * Muestra la vista específica de los salones
     */
    public function salones()
    {
        // Obtenemos todos los salones con sus sensores y su última lectura
        $salones = Salon::with([
            'sensores.lecturas' => function ($query) {
                $query->latest('fecha_hora')->limit(1);
            }
        ])->get();

        // Renderiza la vista de salones
        return view('salones.salones', compact('salones'));
    }
}