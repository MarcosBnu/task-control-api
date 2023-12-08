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

    /**
     * @OA\Get(
     *     path="/ver-tarefas",
     *     summary="Ver todas as tarefas",
     *     tags={"Tasks"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todas as tarefas",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="array", @OA\Items(ref="#/components/schemas/Task")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor")
     *         ),
     *     ),
     * )
     */

    public function indexs(){

        try{
            
            return $this->taskService->getTarefas();
       
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
        
    }

    /**
     * @OA\Get(
     *     path="/ver-tarefa/{id}",
     *     summary="Ver detalhes de uma tarefa",
     *     tags={"Tasks"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa a ser visualizada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da tarefa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="object", ref="#/components/schemas/Task"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Tarefa não encontrada ou não autorizada")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor")
     *         ),
     *     ),
     * )
     */
    public function index($id){

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
     *             @OA\Property(property="status_id", type="integer", example=1),
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

    /**
     * @OA\Delete(
     *     path="/deletar-tarefa/{id}",
     *     summary="Deletar uma tarefa",
     *     tags={"Tasks"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa a ser deletada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa deletada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Tarefa deletada com sucesso")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Tarefa não encontrada ou não autorizada")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor")
     *         ),
     *     ),
     * )
     */
    public function destroy($id){

        try{
            
            $retorno = $this->taskService->deletarTarefa($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
 
    }

    /**
     * @OA\Put(
     *     path="/atualizar-tarefa/{id}",
     *     summary="Atualizar uma tarefa",
     *     tags={"Tasks"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa a ser atualizada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Corpo da requisição contendo os dados da tarefa a ser atualizada",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="nome", type="string", example="Nova Tarefa"),
     *                 @OA\Property(property="descricao", type="string", example="Descrição da nova tarefa"),
     *                 @OA\Property(property="finalizada", type="boolean", example=true),
     *                 @OA\Property(property="dataFinalizado", type="string", format="date", example="2023-11-09"),
     *                 @OA\Property(property="dataDeEntrega", type="string", format="date", example="2023-12-01"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Tarefa atualizada com sucesso")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Tarefa não encontrada ou não autorizada")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Os dados fornecidos são inválidos")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor")
     *         ),
     *     ),
     * )
     */
    public function update(Request $dados, $id){

        try{
            
            $retorno = $this->taskService->atualizarTarefa($dados, $id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
 
    }

    /**
     * @OA\Post(
     *     path="/mudar-status",
     *     summary="Registrar um novo histórico de status da tarefa",
     *     tags={"Tasks"},
     *     security={{ "bearerToken":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="task_id", type="integer", example=1),
     *             @OA\Property(property="status_id", type="integer", example=1),
     *             @OA\Property(property="comentario", type="string", example="Comentário obrigatório"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Histórico de status registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Histórico de status registrado com sucesso"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor")
     *         ),
     *     ),
     * )
     */
    public function alter(Request $dados){

        try{
            
            $retorno = $this->taskService->mudarStatus($dados);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }

    }
}
