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

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
        
    }

    public function tarefa($id){

        try{

            return $this->taskService->getTarefa($id);

        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
        
    }

    /**
     * @OA\Post(
     *     path="/cadastrar-tarefa",
     *     summary="Registrar uma nova tarefa",
     *     tags={"Tasks"},
     *     security={{ "bearerToken":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da tarefa",
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", description="Nome da tarefa", example="Minha Tarefa"),
     *             @OA\Property(property="descricao", type="string", description="Descrição da tarefa", example="Descrição da minha tarefa"),
     *             @OA\Property(property="finalizada", type="boolean", description="Indica se a tarefa está finalizada", example=false),
     *             @OA\Property(property="dataFinalizado", type="string", format="date", nullable=true, description="Data em que a tarefa foi finalizada", example=null),
     *             @OA\Property(property="dataDeEntrega", type="string", format="date", nullable=true, description="Data de entrega da tarefa", example="2023-01-10"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa registrada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Tarefa cadastrada com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica")
     *         )
     *     ),
     * )
     */

    public function register(Request $dados){

        try{
            
            return $this->taskService->registrarTarefa($dados);
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
 
    }

    public function delete($id){

        try{
            
            $retorno = $this->taskService->deletarTarefa($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
 
    }

    public function atualizar(Request $dados, $id){

        try{
            
            $retorno = $this->taskService->atualizarTarefa($dados, $id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
 
    }
}
