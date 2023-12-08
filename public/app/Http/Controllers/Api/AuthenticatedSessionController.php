<?php

namespace App\Http\Controllers\Api;
use App\Services\AuthenticatedSessionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;


class AuthenticatedSessionController extends Controller
{
    private $Authenticated;

    public function __construct(AuthenticatedSessionService $Authenticated)
    {
        $this->Authenticated = $Authenticated;
    }
    

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Autenticar um usuário",
     *     tags={"Authenticated"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciais de login",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="empresa@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="token", type="string", example="token-de-acesso"),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Erro de autenticação",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Erro"),
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Erro"),
     *             @OA\Property(property="message", type="string", example="Mensagem de erro específica"),
     *         ),
     *     ),
     * )
     */

    public function login(Request $request)
    {
        try{

            $userLogin = $this->Authenticated->loginSessionService($request);

            return $userLogin;

        }catch (Exception $e) {
            
            return response()->json(['status'  => 'ERROR', 'message' => $e->getMessage()], 500);
        }
    }

    // public function logout(Request $request)
    // {
    //     // Revogue todos os tokens de acesso do usuário
    //     $request->user()->tokens()->delete();

    //     return response()->json(['message' => 'Logout successful']);
    // }

    // public function user(Request $request)
    // {
    //     // Retorna as informações do usuário autenticado
    //     return response()->json($request->user());
    // }
}
