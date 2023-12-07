<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\StatusService;
use Exception;

class StatusController extends Controller
{

    private $statusService;

    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
    }


    /**
     * @OA\Post(
     *     path="/cadastrar-status",
     *     summary="Cadastrar novo Status",
     *     tags={"Status"},
     *     security={{ "bearerToken":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados para cadastrar um novo Status",
     *         @OA\JsonContent(
     *             required={"nome", "descricao"},
     *             @OA\Property(property="nome", type="string", description="Nome do Status"),
     *             @OA\Property(property="descricao", type="string", description="Descrição do Status"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Status cadastrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Status cadastrado com sucesso!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="A solicitação não contém um corpo JSON válido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro de validação"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor"),
     *         ),
     *     ),
     * )
     */
    public function register(Request $request)
    {
        try {

            return $this->statusService->cadastrarStatus($request);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);        
        }
    }
    /**
     * @OA\Delete(
     *     path="/deletar-status/{id}",
     *     summary="Deletar Status",
     *     tags={"Status"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do Status a ser deletado",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status deletado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Status deletado com sucesso"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Status não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Status não encontrado"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro de validação"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor"),
     *         ),
     *     ),
     * )
     */
    public function destroy($id){

        try{
            
            $retorno = $this->statusService->deletarStatus($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
 
    }

    /**
     * @OA\Put(
     *     path="/atualizar-status/{id}",
     *     summary="Atualizar Status",
     *     tags={"Status"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do Status a ser atualizado",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Corpo da requisição contendo os dados para atualização do Status",
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Novo Nome"),
     *             @OA\Property(property="descricao", type="string", example="Nova Descrição"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Status atualizado com sucesso"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Status não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Status não encontrado"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro de validação"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor"),
     *         ),
     *     ),
     * )
     */

    public function update(Request $request, $id)
    {
        try {

            return $this->statusService->atualizarStatus($request, $id);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }

/**
 * @OA\Get(
 *     path="/ver-status/{id}",
 *     summary="Ver Status",
 *     tags={"Status"},
 *     security={{ "bearerToken":{} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID do Status a ser visualizado",
 *         @OA\Schema(
 *             type="integer",
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Status encontrado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="OK"),
 *             @OA\Property(property="mensagem", type="object", ref="#/components/schemas/Status"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Não autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Status não encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Status não encontrado"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Os dados fornecidos não são válidos"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno no servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ERRO"),
 *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor"),
 *         ),
 *     ),
 * )
 */
    public function index($id)
    {
        try {

            return $this->statusService->getStatus($id);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/ver-todos-status",
     *     summary="Ver Todos os Status",
     *     tags={"Status"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de status encontrada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Status")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Erro interno no servidor"),
     *         ),
     *     ),
     * )
    */

    public function indexs()
    {
        try {

            return $this->statusService->getTodosStatus();

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }
}
