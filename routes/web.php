<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LecturaController;

// Ruta principal (dashboard con salones y lecturas)
Route::get('/', function () {
    $lecturas = DB::table('lecturas')->orderBy('fecha_hora', 'desc')->get();
    return view('index', compact('lecturas'));
});



Route::get('/', [LecturaController::class, 'index']);
Route::get('/salones', [LecturaController::class, 'salones']);