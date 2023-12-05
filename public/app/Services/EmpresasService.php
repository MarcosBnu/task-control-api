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
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            }

            // Se a validação for bem-sucedida, crie o usuário
            $user = Empresas::create([
                'name' => $request->input('name'),
                'cnpj' => $request->input('cnpj'),
            ]);

            $usuario = New UserService;

            $request['tipoUsuario'] = 'admin';

            return $usuario->registerUser($request, $user->id);
        }

        public function getEmpresa(){

            $usuario = Auth::user();

            $statusDoUsuario = $usuario->empresas;

            return response()->json(['empresa' => $statusDoUsuario]);
        }

        public function atualizarEmpresa(Request $dados){

            $usuario = Auth::user();

            if (!$dados->isJson()) {
                return response()->json(['mensagem' => 'A solicitação não contém um corpo JSON válido'], 400);
            }
        
            $jsonArray = json_decode($dados->getContent(), true);
            
            if (empty($jsonArray)) {
                return response()->json(['mensagem' => 'JSON vazio'], 404);
            }

            $validator = Validator::make($dados->all(), [
                'name' => 'string',
                'cnpj' => 'unique:empresas',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            
            }

            $empresa = $usuario->empresas;

            if (!$empresa) {
                return response()->json(['mensagem' => 'empresa não encontrada ou não autorizada'], 404);
            }

            $empresa->update($dados->only(['name', 'cnpj']));

            return response()->json(['mensagem' => 'Empresa atualizado com sucesso']);

        }

        public function deletarEmpresa(){

            $usuario = Auth::user();

            $usuario->empresas->delete();

            return response()->json(['mensagem' => 'Empresa deletada com sucesso']);

        }
    }
