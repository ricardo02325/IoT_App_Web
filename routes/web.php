<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\DispositivoParticleController;
use App\Http\Controllers\SalonController;

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

    // Vistas del panel de administrador
    Route::get('/inicio', [LecturaController::class, 'index'])->name('inicio');
    Route::get('/salones', [LecturaController::class, 'salones'])->name('salones');
    Route::get('/graficas', [LecturaController::class, 'graficas'])->name('graficas');
    Route::get('/tabla', [LecturaController::class, 'tabla'])->name('tabla');
    
    // Rutas para salones
    Route::post('/salones', [SalonController::class, 'store'])->name('salones.store');
    Route::put('/salones/{id}', [SalonController::class, 'update'])->name('salones.update');
    Route::delete('/salones/{id}', [SalonController::class, 'destroy'])->name('salones.destroy'); // ← AGREGAR ESTA LÍNEA
    
    // Ruta para verificar device_id (NUEVA RUTA AGREGADA)
    Route::post('/check-device-id', [SalonController::class, 'checkDeviceId'])->name('check.device.id');
    
    // Ruta para obtener todos los dispositivos Particle
    Route::get('/dispositivos', [DispositivoParticleController::class, 'index'])->name('dispositivos.index');

    // Ruta para guardar lecturas (para usuarios autenticados)
    Route::post('/lecturas', [LecturaController::class, 'store'])->name('lecturas.store');

});

// --- RUTAS PÚBLICAS ---

// Ruta pública para guardar datos (Para los sensores)
// Esta ruta NO está protegida por 'auth' para que los sensores puedan enviar datos.
Route::post('/lecturas', [LecturaController::class, 'store'])->name('lecturas.store.public');

// Ruta para simular lecturas (pública para testing)
Route::get('/simular-lecturas', [LecturaController::class, 'simular'])->name('simular.lecturas');