<? 
    namespace App\Services;


    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use App\Models\Empresas;
    use Exception;
    use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

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
    }
