<? 
    namespace App\Services;

    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Laravel\Sanctum\Sanctum;
    use PhpParser\Node\Expr\New_;
    use App\Models\StatusHistory;

    class StatusHistoryService
    {

        public final function registrar(Request $dados){


            $validator = Validator::make($dados->all(), [
                'status_id' =>  'required|exists:status,id',
                'task_id' =>  'required|exists:task,id',
                'comentario'=> 'required|string|min:1'
            ]);
            
            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json(['message' => 'Dados inválidos', 'errors' => $validator->errors()], 400);
            
            }

            $usuario = Auth::user();

            $temTarefa = $usuario->empresas->task->where('id', $dados->input('task_id'))->first();

            $this->saida($temTarefa->id);

            //cria o novo registro
            StatusHistory::create([
                'user_id'   => $usuario->id,
                'task_id'   => $dados->input('task_id'),
                'status_id' => $dados->input('status_id'),
                'empresa_id'=> $usuario->empresa->id,
                'comentario'=> $dados->input('comentario')
            ]);

            return response()->json(['mensagem' => 'Tarefa em produção','status' => $dados->input('status_id')]);

        }

        private final function saida($task){
 
            $statusAntigo = StatusHistory::where('task_id', $task)
                ->whereNull('saida')->first();

            if($statusAntigo){

                $statusAntigo->update(['saida' => now()]);

            }
        }

        public final function atualizarComentario(Request $dados, $id){

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
                'comentario'=> 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json(['message' => 'Dados inválidos', 'errors' => $validator->errors()], 400);
            
            }

            $historico = $usuario->statushistory->where('id', $id)->first();

            if (!$historico) {
                return response()->json(['mensagem' => 'Historico não encontrada ou não autorizada'], 404);
            }

            $historico->update($dados->only(['comentario']));

            return response()->json(['mensagem' => 'Comentario do historico atualizado com sucesso']);
        }

        public final function deletarHistorico($id){

            $validator = Validator::make($id, [
                'id'    => 'required|numeric',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json(['message' => 'Dados inválidos', 'errors' => $validator->errors()], 400);
            
            }
            
            $usuario = Auth::user();

            $historico = $usuario->statushistory->where('id', $id)->first();

            if(!$historico){

                return response()->json(['message' => 'Dados inválidos ou não encontrados', 'errors' => $validator->errors()], 400);

            }
            else if($historico->saida == null){

                return response()->json(['message' => 'Atualize o status da tarefa: '.$historico->task_id . ' para poder realizar essa exclusão', 'errors' => $validator->errors()], 400);
            
            } else {

                $historico->delete();

                return response()->json(['message' => 'Historico excluido com sucesso!']);
            }

        }

        public final function verHistorico(){

            $usuario = Auth::user();

            return response()->json(['historico' => $usuario->tasks->statushistory]);

        }

    }