<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObraController;

// Hub de Seleção de Obras
Route::get('/', [ObraController::class, 'index'])->name('obras.index');

// Rotas Dedicadas do Dashboard por Obra
Route::prefix('dashboard/{obra?}')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/curva-abc', [DashboardController::class, 'curvaAbc'])->name('dashboard.curva-abc');
    Route::get('/servicos', [DashboardController::class, 'servicos'])->name('dashboard.servicos');
    Route::get('/ocorrencias', [DashboardController::class, 'ocorrencias'])->name('dashboard.ocorrencias');
    Route::post('/orcamento', [DashboardController::class, 'atualizarOrcamento'])->name('dashboard.orcamento');
});

// Importação via Excel com Sobrescrita Limpa
Route::post('/importar', [DashboardController::class, 'importar'])->name('importar.excel');

// Atualização de Status e Observação do Serviço
Route::patch('/servicos/{servico}/status', [DashboardController::class, 'atualizarStatus'])->name('servicos.status');
