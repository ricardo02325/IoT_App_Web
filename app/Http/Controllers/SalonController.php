<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;

class SalonController extends Controller
{
    // Mostrar todos los salones
    public function index()
    {
        $salones = Salon::withCount('alumnos')->get();
        return view('salones.salones', compact('salones'));
    }

    // Mostrar un salón específico (opcional)
    public function show($id)
    {
        $salon = Salon::with('alumnos')->findOrFail($id);
        return view('salones.show', compact('salon'));
    }
}