<?php

namespace App\Http\Controllers\Api;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function tarefas(){

        try{
            
            return $this->taskService->getTarefas();
       
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
        
    }

    public function tarefa($id){

        try{

            return $this->taskService->getTarefa($id);

        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
        
    }

    public function register(Request $dados){

        try{
            
            return $this->taskService->registrarTarefa($dados);
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
 
    }

    public function delete($id){

        try{
            
            $retorno = $this->taskService->deletarTarefa($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
 
    }

    public function atualizar(Request $dados, $id){

        try{
            
            $retorno = $this->taskService->atualizarTarefa($dados, $id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
 
    }
}
