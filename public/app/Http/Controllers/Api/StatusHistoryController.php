<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StatusHistoryService;
use Exception;

class StatusHistoryController extends Controller
{
    private $taskHistoryService;

    public function __construct(StatusHistoryService $taskHistoryService)
    {
        $this->taskHistoryService = $taskHistoryService;
    }

/**
 * @OA\Put(
 *     path="/alterar-comentario/{id}",
 *     summary="Atualizar Comentário do Histórico de Status",
 *     tags={"Histórico de Status da tarefa"},
 *     security={{ "bearerToken":{} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do Histórico de Status a ser atualizado",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="comentario", type="string", description="Novo comentário para o histórico de status")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Histórico de Status atualizado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="OK"),
 *             @OA\Property(property="mensagem", type="string", example="Comentário do Histórico de Status atualizado com sucesso")
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
 *         description="Histórico de Status não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Histórico de Status não encontrado")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Erro de validação. Detalhes nos campos inválidos.")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno do servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Erro interno do servidor")
 *         ),
 *     ),
 * )
 */
    public function update(Request $request, $id)
    {
        try {

            $comentario = $this->taskHistoryService->atualizarComentario($request, $id);

            return $comentario;

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);        
            }
    }

    /**
     * @OA\Delete(
     *     path="/deletar-historico/{id}",
     *     summary="Excluir Histórico de Status da tarefa",
     *     tags={"Histórico de Status da tarefa"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do Histórico de Status a ser excluído",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Histórico de Status excluído com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Histórico de Status excluído com sucesso")
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
     *         description="Histórico de Status não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Histórico de Status não encontrado")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro de validação. Detalhes nos campos inválidos.")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno do servidor")
     *         ),
     *     ),
     * )
     */

    public function delete($id){

        try{
            
            $retorno = $this->taskHistoryService->deletarHistorico($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/ver-historico",
     *     summary="Obter todos os Históricos de Status feitos pelo usuario",
     *     tags={"Histórico de Status da tarefa"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de todos os Históricos de Status",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="historicos", type="array", @OA\Items(ref="#/components/schemas/StatusHistory")),
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
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno do servidor")
     *         ),
     *     ),
     * )
     */
    public function index(){

        try{
            
            $retorno = $this->taskHistoryService->verHistorico();

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }
}
