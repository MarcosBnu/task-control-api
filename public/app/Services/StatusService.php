<? 
    namespace App\Services;

    use App\Models\Status;
    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use App\Models\Task;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Laravel\Sanctum\Sanctum;
    use NunoMaduro\Collision\Adapters\Phpunit\State;

    class StatusService
    {

        public function getTodosStatus(){

            $usuario = Auth::user();

            $tarefasDoUsuario = $usuario->status;

            return response()->json(['tarefas' => $tarefasDoUsuario]);

        }
        
        public function getStatus($id){

            $usuario = Auth::user();

            $validator = Validator::make(['id' => $id], [
                'id'      => 'required|numeric',   
            ]);

            if ($validator->fails()) {
                
                throw new Exception($validator->errors()->first());

            }

            $statusDoUsuario = $usuario->status->where('id', $id)->first();

            if (!$statusDoUsuario) {

                return response()->json(['mensagem' => 'Status não encontrada ou não autorizada'], 404);
            
            }

            return response()->json(['status' => $statusDoUsuario]);

        }
        public function cadastrarStatus(Request $dados){

            $usuario = Auth::user();

            $dados['user_id'] = $usuario->id;

            $validator = Validator::make($dados->all(), [
                'user_id' => 'required|exists:users,id', 
                'nome' => 'required|string|min:1',
                'descricao' => 'required|string|min:1',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            }

            Status::create([
                'nome' => $dados->input('nome'),
                'descricao' => $dados->input('descricao'),
                'user_id' => $dados->input('user_id')
            ]);

            return response()->json(['message' => 'Tarefa cadastrada com sucesso!']);

        }

        public function deletarStatus($dados){

            $usuarioId = Auth::user()->id;

            $validator = Validator::make(['id' => $dados, 'user_id' => $usuarioId], [
                'id'      => 'required|numeric',  
                'user_id' => 'required|exists:users,id', 
            ]);

            if ($validator->fails()) {

                throw new Exception($validator->errors()->first());

            }

            $tarefaComStatus = Task::where('status_id', $dados)->where('user_id', $usuarioId)->first();

            if ($tarefaComStatus) {

                return response()->json(['mensagem' => 'Status vinculado a tarefas'], 404);
            
            }

            $status = Status::where('id', $dados)->where('user_id', $usuarioId)->first();

            if(!$status){

                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);

            }

            $status->delete();

            return response()->json(['mensagem' => 'status deletado com sucesso']);

        }

        public function atualizarStatus(Request $dados, $id){

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
                'user_id' => 'required|exists:users,id',
                'nome' => 'string|min:1',
                'descricao' => 'string|min:1',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            
            }

            $status = Status::where('id', $id)->where('user_id', $usuario->id)->first();

            if (!$status) {
                return response()->json(['mensagem' => 'status não encontrada ou não autorizada'], 404);
            }

            $status->update($dados->only(['nome', 'descricao']));

            return response()->json(['mensagem' => 'Status atualizado com sucesso']);

        }
    }
