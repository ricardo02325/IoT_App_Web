<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LecturaController;


Route::get('/', [LecturaController::class, 'index'])->name('inicio');


Route::get('/salones', [LecturaController::class, 'salones'])->name('salones');
