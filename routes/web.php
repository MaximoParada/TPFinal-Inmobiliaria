<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\InmobiliariaController;
use Illuminate\Support\Facades\Route;

// Vistas principales
Route::get('/', [InmobiliariaController::class, 'inicio'])->name('inicio');
Route::get('/nosotros', [InmobiliariaController::class, 'nosotros'])->name('nosotros');
Route::get('/tasaciones', [InmobiliariaController::class, 'tasaciones'])->name('tasaciones');

// Filtro AJAX del buscador
Route::get('/propiedades/filtrar', [InmobiliariaController::class, 'filtrar'])->name('propiedades.filtrar');

// Endpoints del Chatbot (BotUI)
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