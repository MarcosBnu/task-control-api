<? 
    namespace App\Services;


    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use App\Models\Empresas;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;


    class UserService
    {
        const ADMIN = 'admin';
        const LIDER = 'lider';
        const RH = 'rh';
        const EXPECTADOR = 'expectador';
        const OPERACIONAL = 'operacional';

        public function registerUser(Request $request, $userEmpresa)
        {
            // Use o método validate diretamente no Request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'tipoUsuario' => ['required', Rule::in($this->getTiposUsuario())]
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            }
            // Se a validação for bem-sucedida, crie o usuário
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'empresa_id' => $userEmpresa,
                'password' => bcrypt($request->input('password')),
            ]);

            return $user;
        }

        private static function getTiposUsuario()
        {
            return [
                self::ADMIN,
                self::LIDER,
                self::RH,
                self::EXPECTADOR,
                self::OPERACIONAL,
            ];
        }
    }
