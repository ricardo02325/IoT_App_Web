<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aquí se registran las rutas web para tu aplicación.
|
*/

// --- PASO 1: Rutas de Autenticación ---
// Esta línea crea las rutas /login, /logout, /register, etc.
Auth::routes();


// --- PASO 2: La Nueva Ruta Principal (El Guardia) ---
// Redirige según si el usuario ha iniciado sesión o no.
Route::get('/', function () {
    if (Auth::check()) {
        // Si ya inició sesión, lo mandamos al panel principal (/inicio)
        return redirect()->route('inicio');
    }
    // Si no ha iniciado sesión, lo mandamos a la página de login.
    return redirect()->route('login');
});


// --- PASO 3: Grupo de Rutas Protegidas ---
// Todas las rutas dentro de este grupo SÓLO serán accesibles
// para usuarios que hayan iniciado sesión.
Route::middleware(['auth'])->group(function () {
    
    // Tus rutas personalizadas, ahora seguras y dentro del panel
    Route::get('/inicio', [LecturaController::class, 'index'])->name('inicio');
    Route::get('/salones', [LecturaController::class, 'salones'])->name('salones');

    // La ruta /home ha sido eliminada.

    // ... Aquí puedes añadir todas las futuras rutas de tu panel ...

});