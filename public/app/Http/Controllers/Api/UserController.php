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

    public function register(Request $request)
    {
        try {

            $empresa = New EmpresasService;

            $user = $empresa->registerEmpresas($request);

            return response()->json(['message' => 'Empresa cadastrada com sucesso!', 'user' => $user]);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function registerUsuario(Request $request)
    {
        try {

            $user = $this->userService->registerUser($request, Auth::user()->empresa_id);

            return response()->json(['message' => 'Usuário cadastrado com sucesso!', 'user' => $user]);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function teste()
    // {
    //     return response()->json(['oi' => 'eitaa']);

    // }
}
