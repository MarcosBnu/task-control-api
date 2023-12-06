<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmpresasService;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\New_;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/registrar-usuario",
     *     summary="Registrar um novo usuário",
     *     tags={"Usuários"},
     *     security={{ "bearerToken":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do novo usuário",
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "tipoUsuario"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="12345678"),
     *             @OA\Property(property="tipoUsuario", type="string", example="lider"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário cadastrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Usuário cadastrado com sucesso!"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token de acesso ausente ou inválido",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="error", type="string", example="Token de acesso ausente ou inválido"),
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
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     * )
     */
    public function registerUsuario(Request $request)
    {
        try {

            $user = $this->userService->registerUser($request, Auth::user()->empresa_id);

            return $user;

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }
    }

    /**
         * @OA\Delete(
         *     path="/deletar-usuario/{id}",
         *     summary="Deletar um usuário",
         *     tags={"Usuários"},
         *     security={{ "bearerToken":{} }},
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         description="ID do usuário a ser deletado",
         *         @OA\Schema(type="integer"),
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Usuário deletado com sucesso",
         *         @OA\JsonContent(
         *             @OA\Property(property="status", type="string", example="OK"),
         *             @OA\Property(property="mensagem", type="string", example="Usuário deletado com sucesso"),
         *         ),
         *     ),
         *     @OA\Response(
         *         response=401,
         *         description="Token de acesso ausente ou inválido",
         *         @OA\JsonContent(
         *             @OA\Property(property="status", type="string", example="ERRO"),
         *             @OA\Property(property="error", type="string", example="Token de acesso ausente ou inválido"),
         *         ),
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Usuário não encontrado ou não autorizado",
         *         @OA\JsonContent(
         *             @OA\Property(property="status", type="string", example="ERRO"),
         *             @OA\Property(property="mensagem", type="string", example="Usuário não encontrado ou não autorizado"),
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
         *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
         *         ),
         *     ),
         * )
    */

    public function destroy($id)
    {
        try{
            
            $retorno = $this->userService->deletarUsuario($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);
        }

    }

    /**
     *
     * @OA\Put(
     *     path="/atualizar-usuario/{id}",
     *     summary="Atualiza os detalhes de um usuário",
     *     tags={"Usuários"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do usuário a serem atualizados",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Novo Nome"),
     *             @OA\Property(property="email", type="string", format="email", example="novoemail@example.com"),
     *             @OA\Property(property="password", type="string", example="novasenha123"),
     *             @OA\Property(property="tipoUsuario", type="string", example="admin"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="mensagem", type="string", example="Usuário atualizado com sucesso"),
     *         ),
     *     ), 
     *     @OA\Response(
     *         response=400,
     *         description="A solicitação não contém um corpo JSON válido",
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
     *         response=404,
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Usuário não encontrado ou não autorizado"),
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
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     * )
     */

    public function update(Request $request, $id)
    {
        try {

            return $this->userService->atualizarUsuario($request, $id);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);        
        }
    }

    /**
     * @OA\Get(
     *     path="/ver-usuario/{id}",
     *     summary="Obter informações do usuário",
     *     tags={"Usuários"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informações do usuário",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(
     *                 property="mensagem",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", description="ID do usuário", example=1),
     *                 @OA\Property(property="name", type="string", description="Nome do usuário", example="John Doe"),
     *                 @OA\Property(property="email", type="string", description="E-mail do usuário", example="john@example.com"),
     *                 @OA\Property(property="tipoUsuario", type="string", description="Tipo de usuário", example="admin"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação", example="2023-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização", example="2023-01-01T12:00:00Z"),
     *             ),
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
     *         description="Usuário não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Usuário não encontrado ou não autorizado"),
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
    public function index($id){

        try {

            return $this->userService->getUsuario($id);

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);

        }

    }

    /**
     * @OA\Get(
     *     path="/ver-usuarios",
     *     summary="Obter informações de todos os usuários",
     *     tags={"Usuários"},
     *     security={{ "bearerToken":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(
     *                 property="mensagem",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", description="ID do usuário", example=1),
     *                     @OA\Property(property="name", type="string", description="Nome do usuário", example="John Doe"),
     *                     @OA\Property(property="email", type="string", description="E-mail do usuário", example="john@example.com"),
     *                     @OA\Property(property="tipoUsuario", type="string", description="Tipo de usuário", example="admin"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação", example="2023-01-01T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização", example="2023-01-01T12:00:00Z"),
     *                 ),
     *             ),
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
     *             @OA\Property(property="error", type="string", example="ERRO"),
     *             @OA\Property(property="mensagem", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     * )
     */
    public function indexs(){

        try {

            return $this->userService->getUsuarios();

        } catch (Exception $e) {
            
            return response()->json([
                'status'  => 'ERRO',
                'mensagem' => $e->getMessage()], 500);        
        }
    }
}
