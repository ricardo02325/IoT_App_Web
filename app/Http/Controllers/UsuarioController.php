<?php
use App\Models\Usuario;
use App\Http\Controllers\Controller;

class LecturaController extends Controller
{
    public function index()
    {
        $lecturas = \App\Models\Lectura::orderBy('fecha_hora', 'desc')->get();
        $valorMaximo = $lecturas->max('valor');

        // 🔹 Obtener usuarios con rol "alumno"
        $alumnos = Usuario::alumnos()->get();
        $totalAlumnos = $alumnos->count();

        $totalLecturas = $lecturas->count();

        return view('index', compact('lecturas', 'valorMaximo', 'alumnos', 'totalAlumnos', 'totalLecturas'));
    }
}