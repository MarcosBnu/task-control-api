<? 
    namespace App\Services;


    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use App\Models\Empresas;
    use Exception;
    use Illuminate\Http\Request;
    use PhpParser\Node\Expr\New_;
    use Illuminate\Support\Facades\Auth;

    class EmpresasService
    {
        public function registerEmpresas(Request $request)
        {
            // Use o método validate diretamente no Request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'cnpj' => 'required|unique:empresas',
                'email' => 'required|email|unique:users',
            ]);

            //validar email repetido
            
            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            // Se a validação for bem-sucedida, crie o usuário
            $user = Empresas::create([
                'name' => $request->input('name'),
                'cnpj' => $request->input('cnpj'),
            ]);

            $usuario = New UserService;

            $request['tipoUsuario'] = 'admin';

            $usuario->registerUser($request, $user->id);

            return response()->json([
                'status'  => 'OK',
                'mensagem' => 'Empresa cadastrada com sucesso!'], 201);
        }

        public function getEmpresa(){

            $usuario = Auth::user();

            $statusDoUsuario = $usuario->empresas;

            return response()->json([
            'status'  => 'OK',
            'mensagem' => $statusDoUsuario]);
        }

        public function atualizarEmpresa(Request $dados){

            $usuario = Auth::user();

            if (!$dados->isJson()) {
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'A solicitação não contém um corpo JSON válido'], 400);
            }
        
            $jsonArray = json_decode($dados->getContent(), true);
            
            if (empty($jsonArray)) {
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'JSON vazio'], 404);
            }

            $validator = Validator::make($dados->all(), [
                'name' => 'string',
                'cnpj' => 'unique:empresas',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $empresa = $usuario->empresas;

            if (!$empresa) {
                return response()->json([                    
                    'status'  => 'ERRO',
                    'mensagem' => 'empresa não encontrada ou não autorizada'], 404);
            }

            $empresa->update($dados->only(['name', 'cnpj']));

            return response()->json([
                'status'  => 'OK',
                'mensagem' => 'Empresa atualizado com sucesso']);

        }

        public function deletarEmpresa(){
            
            $usuario = Auth::user();
            $empresa = $usuario->empresas;
        
            if (!$empresa) {
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Empresa não encontrada ou não autorizada'
                ], 404);
            }
        
            $empresa->delete();
        
            return response()->json([
                'status'  => 'OK',
                'mensagem' => 'Empresa deletada com sucesso'
            ]);
        }
    }
