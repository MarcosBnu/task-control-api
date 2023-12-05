<? 
    namespace App\Services;


    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use App\Models\Empresas;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\Auth;


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
                'tipoUsuario' =>$request->input('tipoUsuario')
            ]);

            return $user;
        }

        public function deletarUsuario($dados){

            $usuarioId = Auth::user();

            $validator = Validator::make(['id' => $dados], [
                'id'      => 'required|numeric',  
            ]);

            if ($validator->fails()) {

                throw new Exception($validator->errors()->first());

            }

            $usuarioDelete = $usuarioId->empresas->users->where('id', $dados)->first();

            if($usuarioId->id == $dados){

                return response()->json(['mensagem' => 'Usuario não encontrada ou não autorizada'], 404);
                
            }
            
            if (!$usuarioDelete) {

                return response()->json(['mensagem' => 'Usuario não encontrada ou não autorizada'], 404);
            
            }

            $usuarioDelete->delete();

            return response()->json(['mensagem' => 'Usuario deletado com sucesso']);

        }

        public function atualizarUsuario(Request $dados, $id){

            $usuario = Auth::user();

            if (!$dados->isJson()) {
                return response()->json(['mensagem' => 'A solicitação não contém um corpo JSON válido'], 400);
            }
        
            $jsonArray = json_decode($dados->getContent(), true);
            
            if (empty($jsonArray)) {
                return response()->json(['mensagem' => 'JSON vazio'], 404);
            }
            
            $dados['user_id'] = $usuario->id;

            $dados['id'] = $id;

            $validator = Validator::make($dados->all(), [
                'id'    => 'required|numeric',
                'name' => 'string|min:1',
                'email' => 'string|min:1',
                'password' => 'string|min:1',
                'tipoUsuario' => [Rule::in($this->getTiposUsuario())]
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            
            }

            $usuarioAtualizar = $usuario->empresas->users->where('id', $id)->first();

            if (!$usuarioAtualizar) {
                
                return response()->json(['mensagem' => 'usuario não encontrada ou não autorizada'], 404);

            }

            $usuarioAtualizar->update($dados->only(['name', 'email', 'password', 'tipoUsuario']));

            return response()->json(['mensagem' => 'usuario atualizado com sucesso']);

        }

        public function getUsuario($id){

            $usuario = Auth::user();

            $validator = Validator::make(['id' => $id], [
                'id'      => 'required|numeric',   
            ]);

            if ($validator->fails()) {
                
                throw new Exception($validator->errors()->first());

            }

            $usuarioJson = $usuario->empresas->users->where('id', $id)->first();

            if (!$usuarioJson) {

                return response()->json(['mensagem' => 'Usuario não encontrada ou não autorizada'], 404);
            
            }

            return response()->json(['Usuario' => $usuarioJson]);

        }

        public function getUsuarios(){

            $usuario = Auth::user();

            $usuarioJson = $usuario->empresas->users;

            if (!$usuarioJson) {

                return response()->json(['mensagem' => 'Usuario não encontrada ou não autorizada'], 404);
            
            }

            return response()->json(['Usuario' => $usuarioJson]);

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
