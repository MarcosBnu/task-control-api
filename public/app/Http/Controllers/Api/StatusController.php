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

    public function register(Request $request)
    {
        try {

            $this->statusService->cadastrarStatus($request);

            return response()->json(['message' => 'Status cadastrado com sucesso!']);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id){

        try{
            
            $retorno = $this->statusService->deletarStatus($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
 
    }

    public function atualizar(Request $request, $id)
    {
        try {

            return $this->statusService->atualizarStatus($request, $id);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verStatus($id)
    {
        try {

            return $this->statusService->getStatus($id);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function verStatusTodos()
    {
        try {

            return $this->statusService->getTodosStatus();

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
