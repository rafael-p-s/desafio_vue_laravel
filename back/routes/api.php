<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SetorController;

// EMPRESA ROUTES:
// Modelo para fazer os testes: http://127.0.0.1:8000/api/empresa/store
Route::get('/empresas', [EmpresaController::class, 'empresasOnly']);
Route::post('/empresa/store', [EmpresaController::class, 'store']);
Route::put('/empresa/up/{empresa}', [EmpresaController::class, 'update']);
Route::delete('/empresa/del/{empresa}', [EmpresaController::class, 'delete']);

// SETOR ROUTES:
// Modelo para fazer os testes: http://127.0.0.1:8000/api/setor/store
Route::get('/setores', [SetorController::class, 'setorOnly']);
Route::post('/setor/store', [SetorController::class, 'store']);
Route::put('/setor/up/{setor}', [SetorController::class, 'update']);
Route::delete('/setor/del/{setor}', [SetorController::class, 'delete']);

// EMPRESA + SETOR ROUTES:
// Modelo para fazer os testes: http://127.0.0.1:8000/api/empresa-setores
Route::get('/empresa-setores', [EmpresaController::class, 'listarEmpresaSetores']);
// Modelo para fazer os testes: http://127.0.0.1:8000/api/empresas-setores
Route::get('/empresas-setores', [EmpresaController::class, 'empresasComSetores']);
// Modelo para fazer os testes: http://127.0.0.1:8000/api/empresa/{empresa_id}/setores-disponiveis
Route::get('/empresa/{empresa}/setores-disponiveis', [EmpresaController::class, 'setoresDisponiveis']);
// Modelo para fazer os testes: http://127.0.0.1:8000/api/empresa/{empresa_id}/vincular-setores
Route::post('/empresa/{empresa}/vincular-setores', [EmpresaController::class, 'vincularSetores']);
// Modelo para fazer os testes: http://127.0.0.1:8000/api/empresa/{empresa_id}/setor/{setorId}
Route::delete('/empresa/{empresa}/setor/{setorId}', [EmpresaController::class, 'desvincularSetor']);