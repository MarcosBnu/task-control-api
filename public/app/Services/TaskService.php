<?php

    namespace App\Services;

    use App\Http\Controllers\Controller;
    use App\Models\Task;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use Exception;
    use Illuminate\Support\Facades\Validator;

    class TaskService{

        public function getTarefas(){

            $usuario = Auth::user();

            $tarefasDoUsuario = $usuario->tasks;

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

            $tarefaDoUsuario = $usuario->tasks->where('id', $id)->first();

            if (!$tarefaDoUsuario) {

                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            
            }

            return response()->json(['tarefa' => $tarefaDoUsuario]);

        }

        public function registrarTarefa(Request $dados){

            $usuario = Auth::user();

            $dados['user_id'] = $usuario->id;

            $dados['finalizada'] = $dados['finalizada'] ?? false;

            $validator = Validator::make($dados->all(), [
                'user_id' => 'required|exists:users,id', 
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

            Task::create([
                'nome' => $dados->input('nome'),
                'descricao' => $dados->input('descricao'),
                'finalizada' => $dados->input('finalizada'),
                'dataFinalizado' => $dados->input('dataFinalizado'),
                'dataDeEntrega' => $dados->input('dataDeEntrega'),
                'user_id' => $dados->input('user_id')
            ]);

            return response()->json(['message' => 'Tarefa cadastrada com sucesso!']);

        }

        public function deletarTarefa($dados){

            $usuarioId = Auth::user()->id;

            $validator = Validator::make(['id' => $dados, 'user_id' => $usuarioId], [
                'id'      => 'required|numeric',  
                'user_id' => 'required|exists:users,id', 
            ]);

            if ($validator->fails()) {

                throw new Exception($validator->errors()->first());

            }

            $tarefaDelete = Task::where('id', $dados)->where('user_id', $usuarioId)->first();

            if (!$tarefaDelete) {

                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            
            }

            $tarefaDelete->delete();

            return response()->json(['mensagem' => 'Tarefa deletada com sucesso']);

        }

        public function atualizarTarefa(Request $dados, $id){

            $usuario = Auth::user();

            $dados['user_id'] = $usuario->id;

            $dados['id'] = $id;

            $validator = Validator::make($dados->all(), [
                'id'    => 'required|numeric',
                'user_id' => 'required|exists:users,id', 
                'nome' => 'string|min:1',
                'descricao' => 'string|min:1',
                'finalizada' => 'nullable|boolean',
                'dataFinalizado' => 'nullable|date',
                'dataDeEntrega' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                throw new Exception($validator->errors()->first());
            
            }

            $tarefa = Task::where('id', $id)->where('user_id', $usuario->id)->first();

            if (!$tarefa) {
                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            }

            $tarefa->update($dados->only(['nome', 'descricao', 'finalizada', 'dataDeEntrega']));

            return response()->json(['mensagem' => 'Tarefa atualizada com sucesso']);

        }

    }
