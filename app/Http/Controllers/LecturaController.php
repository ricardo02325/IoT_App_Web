<?php

namespace App\Http\Controllers;

use App\Models\Salon;

class LecturaController extends Controller
{
    public function salones()
    {
        // Traemos todos los salones con sus sensores y últimas lecturas
        $salones = Salon::with(['sensores.lecturas' => function($q) {
            $q->latest('fecha_hora')->limit(1);
        }])->get();

        return view('salones.salones', compact('salones'));
    }
}