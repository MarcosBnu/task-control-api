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

            $statusDoUsuario = $usuario->empresas->status;

            return response()->json(['status'  => 'OK', 'mensagem' => $statusDoUsuario]);

        }
        
        public function getStatus($id){

            $usuario = Auth::user();

            $validator = Validator::make(['id' => $id], [
                'id'      => 'required|numeric',   
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $statusDoUsuario = $usuario->empresas->status->where('id', $id)->first();

            if (!$statusDoUsuario) {

                return response()->json(['status'  => 'ERRO', 'mensagem' => 'Status não encontrada ou não autorizada'], 404);
            
            }

            return response()->json([
            'status'  => 'OK',
            'mensagem' => $statusDoUsuario]);

        }

        public function cadastrarStatus(Request $dados){

            $usuario = Auth::user();

            $dados['empresa_id'] = $usuario->empresa_id;

            $validator = Validator::make($dados->all(), [
                'empresa_id'=> 'required|exists:empresas,id',
                'nome' => 'required|string|min:1',
                'descricao' => 'required|string|min:1',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            Status::create([
                'nome' => $dados->input('nome'),
                'descricao' => $dados->input('descricao'),
                'empresa_id' => $dados->input('empresa_id')

            ]);

            return response()->json([                
            'status'  => 'OK',
            'mensagem' => 'Status cadastrada com sucesso!'], 201);

        }

        public function deletarStatus($dados){

            $usuario = Auth::user();

            $validator = Validator::make(['id' => $dados], [
                'id'      => 'required|numeric',  
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $status = $usuario->empresas->status->where('id', $dados)->first(); 

            if(!$status){

                return response()->json([            
                'status'  => 'ERRO',
                'mensagem' => 'Status não encontrada ou não autorizada'], 404);

            }

            if($usuario->empresas->tasks->where('task_id', $status->id)->first()){
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Status vinculado a uma tarefa'], 422);
            }

            $status->delete();

            return response()->json([
            'status'  => 'OK',
            'mensagem' => 'Status deletado com sucesso']);

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
            
            $dados['id'] = $id;

            $validator = Validator::make($dados->all(), [
                'id'    => 'required|numeric',
                'nome' => 'string|min:1',
                'descricao' => 'string|min:1',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $status = $usuario->empresas->status->where('id', $id)->first();

            if (!$status) {
                return response()->json(['status'  => 'ERRO', 'mensagem' => 'status não encontrada ou não autorizada'], 404);
            }

            $status->update($dados->only(['nome', 'descricao']));

            return response()->json(['status'  => 'OK', 'mensagem' => 'Status atualizado com sucesso']);

        }
    }
