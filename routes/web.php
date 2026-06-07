<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Servicio;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PreinscripcionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MultimediaController;

Route::get('/', function () {
    $servicios = Servicio::where('activo', true)->get();
    return view('welcome', compact('servicios'));
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/preinscripcion', [PreinscripcionController::class, 'create'])->name('preinscripcion.create');
Route::post('/preinscripcion', [PreinscripcionController::class, 'store'])->middleware('throttle:5,1')->name('preinscripcion.store');

Route::get('/disponibilidad', [ReservaController::class, 'index'])->middleware('auth')->name('reservas.index');
Route::get('/disponibilidad/eventos', [ReservaController::class, 'disponibilidad'])->name('reservas.disponibilidad');
Route::post('/reservas', [ReservaController::class, 'store'])->middleware(['auth', 'throttle:10,1'])->name('reservas.store');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/preinscripciones', [AdminController::class, 'preinscripciones'])->name('preinscripciones');
    Route::post('/preinscripciones/{id}/aprobar', [AdminController::class, 'aprobarPreinscripcion'])->name('preinscripciones.aprobar');
    Route::post('/preinscripciones/{id}/rechazar', [AdminController::class, 'rechazarPreinscripcion'])->name('preinscripciones.rechazar');
    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas');
    Route::post('/reservas/{id}/confirmar', [AdminController::class, 'confirmarReserva'])->name('reservas.confirmar');
    Route::post('/reservas/{id}/cancelar', [AdminController::class, 'cancelarReserva'])->name('reservas.cancelar');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::get('/multimedia', [MultimediaController::class, 'adminIndex'])->name('multimedia');
});

Route::middleware('auth')->group(function () {
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/checkout/{reserva}', [PagoController::class, 'checkout'])->name('pagos.checkout');
    Route::post('/pagos/procesar/{reserva}', [PagoController::class, 'procesar'])->middleware('throttle:3,1')->name('pagos.procesar');
    Route::get('/pagos/exito/{reserva}', [PagoController::class, 'exito'])->name('pagos.exito');

    // Multimedia — cliente
    Route::get('/multimedia/{perro}', [MultimediaController::class, 'index'])->name('multimedia.index');

    // Multimedia — solo admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/multimedia', [MultimediaController::class, 'store'])->name('multimedia.store');
        Route::delete('/multimedia/{multimedia}', [MultimediaController::class, 'destroy'])->name('multimedia.destroy');
    });
});
require __DIR__.'/auth.php';