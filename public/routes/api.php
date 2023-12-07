<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthenticatedSessionController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\StatusHistoryController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/registrar-empresa', [EmpresaController::class, 'register']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/ver-empresa', [EmpresaController::class, 'index']);
    Route::put('/atualizar-empresa', [EmpresaController::class, 'update']);
    Route::delete('/deletar-empresa', [EmpresaController::class, 'destroy']);


    Route::post('/registrar-usuario', [UserController::class, 'registerUsuario']);
    Route::delete('/deletar-usuario/{id}', [UserController::class, 'destroy']);
    Route::put('/atualizar-usuario/{id}', [UserController::class, 'update']);
    Route::get('/ver-usuario/{id}', [UserController::class, 'index']);
    Route::get('/ver-usuarios', [UserController::class, 'indexs']);


    Route::post('/cadastrar-tarefa', [TaskController::class, 'register']);
    Route::delete('/deletar-tarefa/{id}', [TaskController::class, 'destroy']);
    Route::put('/atualizar-tarefa/{id}', [TaskController::class, 'update']);
    Route::get('/ver-tarefa/{id}', [TaskController::class, 'index']);
    Route::get('/ver-tarefas', [TaskController::class, 'indexs']);

    Route::post('/cadastrar-status', [StatusController::class, 'register']);
    Route::delete('/deletar-status/{id}', [StatusController::class, 'destroy']);
    Route::put('/atualizar-status/{id}', [StatusController::class, 'update']);
    Route::get('/ver-status/{id}', [StatusController::class, 'index']);
    Route::get('/ver-todos-status', [StatusController::class, 'indexs']);

    Route::post('/registrar-historico', [StatusHistoryController::class, 'register']);
    Route::put('/alterar-comentario/{id}', [StatusHistoryController::class, 'update']);
    Route::delete('/deletar-historico/{id}', [StatusHistoryController::class, 'delete']);
    Route::get('/ver-historico', [StatusHistoryController::class, 'index']);
});
