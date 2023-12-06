<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmpresasService;
use Exception;


class EmpresaController extends Controller
{

    private $empresaService;

    public function __construct(EmpresasService $empresaService)
    {
        $this->empresaService = $empresaService;
    }

    /**
     * @OA\Post(
     *     path="/registrar-empresa",
     *     summary="Registrar uma nova empresa",
     *     tags={"Empresas"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados da nova empresa",
     *         @OA\JsonContent(
     *             required={"name", "cnpj", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Minha Empresa"),
     *             @OA\Property(property="cnpj", type="string", example="12.345.678/0001-90"),
     *             @OA\Property(property="email", type="string", format="email", example="empresa@example.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empresa cadastrada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Empresa cadastrada com sucesso!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Erro de validação"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro de validação específica"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Erro"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     * )
     */

    public function register(Request $request)
    {
        try {

            return $this->empresaService ->registerEmpresas($request);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/ver-empresa",
     *     summary="Obter informações da empresa",
     *     tags={"Empresas"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Informações da empresa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Empresas"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Token de acesso ausente ou inválido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
     
        try {

            return $this->empresaService->getEmpresa();

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }

    }

    /**
     * @OA\Put(
     *     path="/atualizar-empresa",
     *     summary="Atualizar informações da empresa",
     *     tags={"Empresas"},
     *     security={{ "bearerToken":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados para atualização da empresa",
     *         @OA\JsonContent(
     *             required={"name", "cnpj"},
     *             @OA\Property(property="name", type="string", example="Nova Empresa"),
     *             @OA\Property(property="cnpj", type="string", example="98.765.432/0001-21"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Empresa atualizada com sucesso!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitação inválida",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="A solicitação não contém um corpo JSON válido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token de acesso ausente ou inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Token de acesso ausente ou inválido"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empresa não encontrada ou não autorizada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Empresa não encontrada ou não autorizada"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro de validação específica"),
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

    public function update(Request $request)
    {
        try {

            return $this->empresaService->atualizarEmpresa($request);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/deletar-empresa",
     *     summary="Deletar empresa",
     *     tags={"Empresas"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Empresa deletada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Empresa deletada com sucesso!"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token de acesso ausente ou inválido",
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
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro de validação específica"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Empresa não encontrada ou não autorizada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Empresa não encontrada ou não autorizada"),
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

    public function destroy()
    {
        try{
            
            return $this->empresaService->deletarEmpresa();
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }
    }
}
