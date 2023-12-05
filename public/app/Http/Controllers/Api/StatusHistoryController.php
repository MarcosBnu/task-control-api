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

    public function register(Request $request)
    {
        try {

            $status = $this->taskHistoryService->registrar($request);

            return $status;

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $comentario = $this->taskHistoryService->atualizarComentario($request, $id);

            return $comentario;

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id){

        try{
            
            $retorno = $this->taskHistoryService->deletarHistorico($id);

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
    }

    public function index(){

        try{
            
            $retorno = $this->taskHistoryService->verHistorico();

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
    }
}
