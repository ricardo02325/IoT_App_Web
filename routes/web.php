<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\DispositivoParticleController;
use App\Http\Controllers\SalonController;
// use App\Http\Controllers\HomeController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se registran las rutas web para tu aplicación.
|
*/

// --- Rutas de Autenticación ---
// Crea /login, /logout, /register, etc.
Auth::routes(['register' => false]);


// --- Redirección Principal ---
// Redirige según si el usuario ha iniciado sesión o no.
Route::get('/', function () {
    if (Auth::check()) {
        // Si ya inició sesión, lo mandamos al panel principal (/inicio)
        return redirect()->route('inicio');
    }
    // Si no ha iniciado sesión, lo mandamos a la página de login.
    return redirect()->route('login');
});


// --- Grupo de Rutas Protegidas (Vistas para el usuario) ---
// Solo usuarios autenticados pueden acceder aquí.
Route::middleware(['auth'])->group(function () {

    // Tus vistas del panel de administrador
    Route::get('/inicio', [LecturaController::class, 'index'])->name('inicio');
    Route::get('/salones', [LecturaController::class, 'salones'])->name(name: 'salones');
    Route::post('/salones', [SalonController::class, 'store'])->name('salones.store');
    Route::get('/graficas', [LecturaController::class, 'graficas'])->name(name: 'graficas');
    Route::get('/tabla', [LecturaController::class, 'tabla'])->name('tabla');
    Route::put('/salones/{id}', [SalonController::class, 'update'])->name('salones.update');
    // Ruta para guardar lecturas (POST)
    Route::post('/lecturas', [LecturaController::class, 'store'])->name('lecturas.store');

    // Ruta para obtener todos los dispositivos Particle (GET)
    Route::get('/dispositivos', [DispositivoParticleController::class, 'index'])->name('dispositivos.index');


    // ... Aquí puedes añadir todas las futuras rutas de tu panel ...

});


// --- RUTA PÚBLICA PARA GUARDAR DATOS (Para los sensores) ---
// Esta ruta NO está protegida por 'auth' para que los sensores puedan enviar datos.
Route::post('/lecturas', [LecturaController::class, 'store'])->name('lecturas.store');



Route::get('/simular-lecturas', [LecturaController::class, 'simular']);