<? 
    namespace App\Services;


    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Laravel\Sanctum\Sanctum;


    class AuthenticatedSessionService
    {
        public function loginSessionService(Request $request)
        {
            
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                // Se as credenciais estiverem corretas, gere e retorne o token
                $token = $request->user()->createToken('auth-token')->plainTextToken;
                return response()->json(['token' => $token, 'message' => 'Login successful']);

            } else {
                // Se as credenciais estiverem incorretas, retorne uma resposta de erro
                return response()->json(['error' => 'Invalid credentials'], 401);
                
            }
        }
    }
