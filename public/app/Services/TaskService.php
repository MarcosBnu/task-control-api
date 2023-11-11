<?php

    namespace App\Services;

    use App\Http\Controllers\Controller;
    use App\Models\StatusHistory;
    use App\Models\Task;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Exception;
    use Illuminate\Support\Facades\Validator;

    class TaskService{

        public function getTarefas(){

            $usuario = Auth::user();

            $tarefasDoUsuario = $usuario->empresas->tasks;

            return response()->json(['tarefas' => $tarefasDoUsuario]);

        }

        public function getTarefa($id){

            $usuario = Auth::user();

            $validator = Validator::make(['id' => $id], [
                'id'      => 'required|numeric',   
            ]);

            if ($validator->fails()) {
                
                throw new Exception($validator->errors()->first());

            }

            $tarefaDoUsuario = $usuario->empresas->tasks->where('id', $id)->first();

            if (!$tarefaDoUsuario) {

                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            
            }

            return response()->json(['tarefa' => $tarefaDoUsuario]);

        }

        public function registrarTarefa(Request $dados){

            $usuario = Auth::user();

            $dados['user_id'] = $usuario->id;

            $dados['empresa_id'] = $usuario->empresas->id;

            $dados['finalizada'] = $dados['finalizada'] ?? false;

            $validator = Validator::make($dados->all(), [
                'user_id' => 'required|exists:users,id',
                'empresa_id' => 'required|exists:empresas,id',
                'status_id' =>  'required|exists:status,id',
                'nome' => 'required|string|min:1',
                'descricao' => 'required|string|min:1',
                'finalizada' => 'nullable|boolean',
                'dataFinalizado' => 'nullable|date',
                'dataDeEntrega' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            }

            if($usuario->empresas->status->where('id', $dados->input('status_id'))->first()){

                $task = Task::create([
                    'nome' => $dados->input('nome'),
                    'descricao' => $dados->input('descricao'),
                    'finalizada' => $dados->input('finalizada'),
                    'dataFinalizado' => $dados->input('dataFinalizado'),
                    'dataDeEntrega' => $dados->input('dataDeEntrega'),
                    'user_id' => $dados->input('user_id'),
                    'status_id' => $dados->input('status_id'),
                    'empresa_id' => $dados->input('empresa_id')
                ]);

                $historico = New StatusHistoryService;

                $historico->registrar($dados->input('user_id'), $dados->input('status_id'), $task->id);
    
                return response()->json(['message' => 'Tarefa cadastrada com sucesso!']);
            } else {

                return response()->json(['message' => 'status invalido!']);

            }


        }

        public function deletarTarefa($dados){

            $usuarioId = Auth::user();

            $validator = Validator::make(['id' => $dados], [
                'id'      => 'required|numeric',  
            ]);

            if ($validator->fails()) {

                throw new Exception($validator->errors()->first());

            }

            $tarefaDelete = $usuarioId->empresas->tasks->where('id', $dados)->first();

            if (!$tarefaDelete) {

                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            
            }

            $tarefaDelete->delete();

            return response()->json(['mensagem' => 'Tarefa deletada com sucesso']);

        }

        public function atualizarTarefa(Request $dados, $id){

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
                'finalizada' => 'nullable|boolean',
                'dataFinalizado' => 'nullable|date',
                'dataDeEntrega' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json(['message' => 'Dados inválidos', 'errors' => $validator->errors()], 400);
            
            }

            $tarefa = $usuario->empresas->tasks->where('id', $id)->first();

            if (!$tarefa) {
                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            }

            if(($dados->input('status_id') && $usuario->status->where('id', $dados->input('status_id'))->first()) || empty($dados->input('status_id'))){

                $statusIdAntes = $tarefa->status_id;

                $tarefa->update($dados->only(['nome', 'descricao', 'finalizada', 'dataDeEntrega', 'status_id']));

                $statusIdDepois = $tarefa->status_id;
                
                if ($statusIdAntes != $statusIdDepois) {

                    $hitorico = New StatusHistoryService;

                    $hitorico->saida($usuario->id, $id, $statusIdAntes, $statusIdDepois);
                }

                return response()->json(['mensagem' => 'Tarefa atualizada com sucesso']);
            
            } else {

                return response()->json(['message' => 'status invalido!']);

            }


        }

    }
