<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\InmobiliariaController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\TurnoController;
use Illuminate\Support\Facades\Route;

// Vistas principales
Route::get('/', [InmobiliariaController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [InmobiliariaController::class, 'nosotros'])->name('nosotros');
Route::get('/tasaciones', [InmobiliariaController::class, 'tasaciones'])->name('tasaciones');
Route::get('/turnero', [TurnoController::class, 'turnero'])->name('turnero');

// Filtro AJAX del buscador
Route::get('/propiedades/filtrar', [InmobiliariaController::class, 'filtrar'])->name('propiedades.filtrar');

// chatbot con botui
Route::prefix('chatbot')->group(function () {
    Route::get('/respuesta/{clave}', [ChatbotController::class, 'obtenerRespuesta']);
    Route::post('/buscar-palabra', [ChatbotController::class, 'buscarPorPalabra']);
    Route::get('/propiedad/{id}', [ChatbotController::class, 'consultarPropiedad']);
    Route::post('/visita', [ChatbotController::class, 'agendarVisita']);
    Route::post('/tasacion', [ChatbotController::class, 'calcularTasacion']);
});

// Autenticación nativa con Auth de Laravel
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/registro', [AuthController::class, 'register'])->name('registro.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas internas (con AJAX) que requieren que el usuario haya iniciado sesión
Route::middleware('auth')->group(function () {
    Route::get('/turnero/eventos/{propiedad_id}', [TurnoController::class, 'eventos'])->name('turnero.eventos');
    Route::post('/turnero/habilitar', [TurnoController::class, 'habilitar'])->name('turnero.habilitar');
    Route::delete('/turnero/eliminar/{id}', [TurnoController::class, 'eliminar'])->name('turnero.eliminar');
    Route::post('/turnero/reservar', [TurnoController::class, 'reservar'])->name('turnero.reservar');
});

// rutas del middleware y CRUD
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/propiedades/crear', [PropiedadController::class, 'create'])->name('propiedades.create');
    Route::post('/propiedades', [PropiedadController::class, 'store'])->name('propiedades.store');
    Route::get('/propiedades/{propiedad}/editar', [PropiedadController::class, 'edit'])->name('propiedades.edit');
    Route::put('/propiedades/{propiedad}', [PropiedadController::class, 'update'])->name('propiedades.update');
    Route::delete('/propiedades/{propiedad}', [PropiedadController::class, 'destroy'])->name('propiedades.destroy');
});