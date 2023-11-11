<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthenticatedSessionController;
use App\Http\Controllers\Api\TaskController;


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
Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [AuthenticatedSessionController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cadastrar-tarefa', [TaskController::class, 'register']);
    Route::delete('/deletar-tarefa/{id}', [TaskController::class, 'delete']);
    Route::put('/atualizar-tarefa/{id}', [TaskController::class, 'atualizar']);
    Route::get('/ver-tarefa/{id}', [TaskController::class, 'tarefa']);
    Route::get('/ver-tarefas', [TaskController::class, 'tarefas']);
});
