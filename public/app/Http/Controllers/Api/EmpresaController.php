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

    public function register(Request $request)
    {
        try {

            $user = $this->empresaService ->registerEmpresas($request);

            return response()->json(['message' => 'Empresa cadastrada com sucesso!', 'user' => $user]);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
     
        try {

            return $this->empresaService->getEmpresa();

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function update(Request $request)
    {
        try {

            return $this->empresaService->atualizarEmpresa($request);

        } catch (Exception $e) {
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy()
    {
        try{
            
            $retorno = $this->empresaService->deletarEmpresa();

            return $retorno;
            
        } catch(Exception $e){

            return response()->json(['error' => $e->getMessage()]);

        }
    }
}
