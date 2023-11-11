<? 
    namespace App\Services;


    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Exception;
    use Illuminate\Http\Request;


    class UserService
    {
        public function registerUser(Request $request)
        {
            // Use o método validate diretamente no Request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            }

            // Se a validação for bem-sucedida, crie o usuário
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            return $user;
        }
    }
