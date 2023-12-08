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

            //so validar o tipo de usuario
            $tarefasDoUsuario = $usuario->empresas->tasks;

            return response()->json([
            'status'  => 'OK',
            'mensagem' => $tarefasDoUsuario]);

        }

        public function getTarefa($id){

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
            //so validar o tipo de usuario
            $tarefaDoUsuario = $usuario->empresas->tasks->where('id', $id)->first();

            if (!$tarefaDoUsuario) {

                return response()->json([                    
                'status'  => 'ERRO',
                'mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            
            }

            return response()->json([
                'status'  => 'OK',
                'mensagem' => $tarefaDoUsuario]);

        }

        public function registrarTarefa(Request $dados){

            $usuario = Auth::user();

            $dados['empresa_id'] = $usuario->empresas->id;

            $dados['finalizada'] = $dados['finalizada'] ?? false;

            if($dados['finalizada'] && !$dados['dataFinalizado']){
            
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Voce não pode finalizar uma tarefa sem passar a data de termino'], 422);
            
            }

            if(!$dados['finalizada'] && $dados['dataFinalizado']){
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Voce não pode passar a data de termino sem finalizar a tarefa'], 422);
            }

            $validator = Validator::make($dados->all(), [
                'empresa_id' => 'required|exists:empresas,id',
                'nome' => 'required|string|min:1',
                'status_id' =>  'required|exists:status,id',
                'descricao' => 'required|string|min:1',
                'finalizada' => 'nullable|boolean',
                'dataFinalizado' => 'nullable|date',
                'dataDeEntrega' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            if(!$usuario->empresas->status->where('id', $dados->input('status_id'))->first()){

                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Status Invalido'], 422);
            }

            $task = Task::create([
                'nome' => $dados->input('nome'),
                'descricao' => $dados->input('descricao'),
                'finalizada' => $dados->input('finalizada'),
                'dataFinalizado' => $dados->input('dataFinalizado'),
                'dataDeEntrega' => $dados->input('dataDeEntrega'),
                'empresa_id' => $dados->input('empresa_id'),
                'status_id' => $dados->input('status_id')
            ]);

            $historico = New StatusHistoryService;

            $dados['comentario'] = 'Inicio da tarefa';
            $dados['task_id'] = $task->id; 

            $historico->registrar($dados);

            return response()->json([
                'status'  => 'OK',
                'mensagem' => 'Task cadastrada com sucesso!'], 201);


        }

        public function deletarTarefa($dados){

            $usuarioId = Auth::user();

            $validator = Validator::make(['id' => $dados], [
                'id'      => 'required|numeric',  
            ]);

            if ($validator->fails()) {
                // Se a validação falhar, lance uma exceção
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $tarefaDelete = $usuarioId->empresas->tasks->where('id', $dados)->first();

            if (!$tarefaDelete) {

                return response()->json([                
                'status'  => 'ERRO',
                'mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            
            }

            $tarefaDelete->delete();

            return response()->json([                
            'status'  => 'OK',
            'mensagem' => 'Tarefa deletada com sucesso']);

        }

        public function atualizarTarefa(Request $dados, $id){

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

            $dados['finalizada'] = $dados['finalizada'] ?? false;

            if($dados['finalizada'] && !$dados['dataFinalizado']){
            
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Voce não pode finalizar uma tarefa sem passar a data de termino'], 422);
            
            }

            if(!$dados['finalizada'] && $dados['dataFinalizado']){
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => 'Voce não pode passar a data de termino sem finalizar a tarefa'], 422);
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
                return response()->json([
                    'status'  => 'ERRO',
                    'mensagem' => $validator->errors()->first()], 422);
            }

            $tarefa = $usuario->empresas->tasks->where('id', $id)->first();

            if (!$tarefa) {
                return response()->json(['mensagem' => 'Tarefa não encontrada ou não autorizada'], 404);
            }

            $tarefa->update($dados->only(['nome', 'descricao', 'finalizada', 'dataDeEntrega']));

            return response()->json([
            'status'  => 'OK',
            'mensagem' => 'Tarefa atualizada com sucesso']);

        }

        public function mudarStatus(Request $dados){

            $historico = New StatusHistoryService;

            $gerarHistorico = $historico->registrar($dados, true);

            if($gerarHistorico === true){

                $usuario = Auth::user();

                $tarefa = $usuario->empresas->tasks->where('id', $dados->input('task_id'))->first();

                if($tarefa){

                    $tarefa->update($dados->only([$dados->input('status_id')]));
    
                    return response()->json(['status'  => 'OK', 'mensagem' => 'Tarefa mudou de status']);
                
                } else {

                    return response()->json([
                        'status'  => 'ERRO',
                        'mensagem' => 'Tarefa invalida'], 422);
                        
                }
            
            } else {

                return $gerarHistorico;

            }
        }

    }
