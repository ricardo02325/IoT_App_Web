<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Ruta principal (dashboard con salones y lecturas)
Route::get('/', function () {
    $lecturas = DB::table('lecturas')->orderBy('fecha_hora', 'desc')->get();
    return view('index', compact('lecturas'));
});

// Ruta de salones (opcional, si quieres acceso directo)
Route::get('/salones', function () {
    $lecturas = DB::table('lecturas')->orderBy('fecha_hora', 'desc')->get();
    return view('salones.salones', compact('lecturas'));
});