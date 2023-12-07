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

        public final function registrar(Request $dados, $inicioTarefa = false){


            $validator = Validator::make($dados->all(), [
                'status_id' =>  'required|exists:status,id',
                'task_id' =>  'required|exists:tasks,id',
                'comentario'=> 'required|string|min:1'
            ]);
            
            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }
            
            $usuario = Auth::user();

            if(!$usuario->empresas->status->where('id', $dados->input('status_id'))->first()){

                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Status Invalido'], 422);
            }

            if(!$usuario->empresas->tasks->where('id', $dados->input('task_id'))->first()){
                
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Tarefa invalida'], 422);

            }

            if($inicioTarefa){

                $retorno = self::saida($dados->input('task_id'), $dados->input('status_id'));

                if(!$retorno){

                    return response()->json([
                        'status'  => 'ERRO',
                        'mensagem' => 'Essa tarefa ja pertence a esse status'], 422);

                }
                
            }
            //cria o novo registro
            StatusHistory::create([
                'user_id'   => $usuario->id,
                'task_id'   => $dados->input('task_id'),
                'status_id' => $dados->input('status_id'),
                'empresa_id'=> $usuario->empresas->id,
                'comentario'=> $dados->input('comentario')
            ]);

            return true;

        }

        private final function saida($task, $newStatus){
 
            $statusAntigo = StatusHistory::where('task_id', $task)
                ->whereNull('saida')->first();

            if($statusAntigo){

                if($statusAntigo->status_id == $newStatus){

                    return false;

                } else {

                    $statusAntigo->update(['saida' => now()]);

                    return true;
                }


            }
        }

        public final function atualizarComentario(Request $dados, $id){

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

            $dados['id'] = $id;

            $validator = Validator::make($dados->all(), [
                'id'    => 'required|numeric',
                'comentario'=> 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $historico = $usuario->statushistory->where('id', $id)->first();

            if (!$historico) {
                return response()->json(['status'  => 'ERRO', 'mensagem' => 'Historico não encontrada ou não autorizada'], 404);
            }

            $historico->update($dados->only(['comentario']));

            return response()->json(['status'  => 'OK', 'mensagem' => 'Comentario atualizado com sucesso']);
        }

        public final function deletarHistorico($id){

            $validator = Validator::make(['id' => $id], [
                'id'      => 'required|numeric',  
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }
            
            $usuario = Auth::user();

            $historico = $usuario->empresas->statushistory->where('id', $id)->first();

            if(!$historico){

                return response()->json(['status' => 'ERRO', 'mensagem' => 'Dados inválidos ou não encontrados', 'errors' => $validator->errors()], 400);

            }
            else if($historico->saida == null){

                return response()->json(['status' => 'ERRO', 'mensagem' => 'Atualize o status da tarefa: '.$historico->task_id . ' para poder realizar essa exclusão'], 422);
            
            } else {

                $historico->delete();

                return response()->json(['status' => 'OK', 'mensagem' => 'Historico excluido com sucesso!']);
            }

        }

        public final function verHistorico(){

            $usuario = Auth::user();

            return response()->json(['status' => 'OK', 'mensagem' => $usuario->empresas->statushistory]);

        }

    }