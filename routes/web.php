<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LecturaController;
// No necesitas HomeController si no lo estás usando, lo he comentado.
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
Auth::routes();


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
    Route::get('/inicio', [UsuarioController::class, 'index'])->name('inicio');
    Route::get('/salones', [LecturaController::class, 'salones'])->name('salones');

    // ... Aquí puedes añadir todas las futuras rutas de tu panel ...

});


// --- RUTA PÚBLICA PARA GUARDAR DATOS (Para los sensores) ---
// Esta ruta NO está protegida por 'auth' para que los sensores puedan enviar datos.
Route::post('/lecturas', [LecturaController::class, 'store'])->name('lecturas.store');



Route::get('/simular-lecturas', [LecturaController::class, 'simular']);