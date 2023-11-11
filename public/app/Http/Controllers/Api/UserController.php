<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;


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

            $user = $this->userService->registerUser($request);

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
