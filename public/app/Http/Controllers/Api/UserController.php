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

    public function registerUsuario(Request $request)
    {
        try {

            $user = $this->userService->registerUser($request, Auth::user()->empresa_id);

            return response()->json(['message' => 'Usuário cadastrado com sucesso!', 'user' => $user]);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteUsuario($id)
    {
        try{
            
            $retorno = $this->userService->deletarUsuario($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }

    }

    public function atualizarUsuario(Request $request, $id)
    {
        try {

            return $this->userService->atualizarUsuario($request, $id);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function verUsuario($id){

        try {

            return $this->userService->getUsuario($id);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function verUsuarios(){

        try {

            return $this->userService->getUsuarios();

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
