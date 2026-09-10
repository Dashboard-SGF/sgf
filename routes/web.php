<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObraController;

// Hub de Seleção de Obras
Route::get('/', [ObraController::class, 'index'])->name('obras.index');

// Dashboard Específico por Obra
Route::get('/dashboard/{obra?}', [DashboardController::class, 'index'])->name('dashboard');

// Importação via Excel
Route::post('/importar', [DashboardController::class, 'importar'])->name('importar.excel');
