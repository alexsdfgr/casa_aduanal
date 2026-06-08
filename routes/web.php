<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedimentoController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// ── Login / Logout ────────────────────────────────────────
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Rutas protegidas (requieren sesión) ───────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [PedimentoController::class, 'dashboard'])
        ->name('dashboard');

    // Pedimentos
    Route::post('pedimentos/{pedimento}/liberar', [PedimentoController::class, 'liberar'])->name('pedimentos.liberar');
    Route::resource('pedimentos', PedimentoController::class);

    // Partidas (anidadas bajo pedimentos)
    Route::resource('pedimentos.partidas', PartidaController::class)
        ->except(['index', 'show']);

    // Usuarios
    Route::resource('usuarios', UsuarioController::class)
        ->except(['show']);
});