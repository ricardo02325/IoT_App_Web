<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LecturaController;

// Ruta principal (dashboard con salones y lecturas)
Route::get('/', [LecturaController::class, 'index'])->name('inicio');

// Ruta para ver los salones
Route::get('/salones', [LecturaController::class, 'salones'])->name('salones');