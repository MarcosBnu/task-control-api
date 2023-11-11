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
    
    public function login(Request $request)
    {
        try{

            $userLogin = $this->Authenticated->loginSessionService($request);

            return $userLogin;

        }catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
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
