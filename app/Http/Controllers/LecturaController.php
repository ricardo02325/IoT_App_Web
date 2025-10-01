<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Lecturas;

class LecturaController extends Controller
{
    public function index()
    {
        // Obtener todas las lecturas ordenadas por fecha_hora descendente
        $lecturas = Lectura::orderBy('fecha_hora', 'desc')->get();
        return view('index', compact('lecturas'));
    }

    public function salones()
    {
        $lecturas = Lectura::orderBy('fecha_hora', 'desc')->get();
        return view('salones.salones', compact('lecturas'));
    }
}