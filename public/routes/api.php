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
Route::post('/register', [EmpresaController::class, 'register']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/ver-empresa', [EmpresaController::class, 'index']);
    Route::put('/atualizar-empresa', [EmpresaController::class, 'update']);
    Route::delete('/delete-empresa', [EmpresaController::class, 'destroy']);


    Route::post('/register-usuario', [UserController::class, 'registerUsuario']);
    Route::delete('/delete-usuario/{id}', [UserController::class, 'deleteUsuario']);
    Route::put('/atualizar-usuario/{id}', [UserController::class, 'atualizarUsuario']);
    Route::get('/ver-usuario/{id}', [UserController::class, 'verUsuario']);
    Route::get('/ver-usuarios', [UserController::class, 'verUsuarios']);


    Route::post('/cadastrar-tarefa', [TaskController::class, 'register']);
    Route::delete('/deletar-tarefa/{id}', [TaskController::class, 'delete']);
    Route::put('/atualizar-tarefa/{id}', [TaskController::class, 'atualizar']);
    Route::get('/ver-tarefa/{id}', [TaskController::class, 'tarefa']);
    Route::get('/ver-tarefas', [TaskController::class, 'tarefas']);

    Route::post('/cadastrar-status', [StatusController::class, 'register']);
    Route::delete('/deletar-status/{id}', [StatusController::class, 'delete']);
    Route::put('/atualizar-status/{id}', [StatusController::class, 'atualizar']);
    Route::get('/ver-status/{id}', [StatusController::class, 'verStatus']);
    Route::get('/ver-todos-status', [StatusController::class, 'verStatusTodos']);

    Route::post('/mudar-coluna', [StatusHistoryController::class, 'register']);
    Route::put('/atualizar-comentario/{id}', [StatusHistoryController::class, 'update']);
    Route::delete('/deletar-historico/{id}', [StatusHistoryController::class, 'delete']);
    Route::get('/ver-historico', [StatusHistoryController::class, 'index']);
});
