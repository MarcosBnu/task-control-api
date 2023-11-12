<? 
    namespace App\Services;

    use App\Models\StatusHistory;
    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Laravel\Sanctum\Sanctum;

    class StatusHistoryService
    {

        public final function registrar($usuario, $status, $task, $empresa_id){

            StatusHistory::create([
                'user_id'   => $usuario,
                'task_id'   => $task,
                'status_id' => $status,
                'empresa_id'=> $empresa_id
            ]);
        }

        public final function saida($usuario, $task, $status, $newStatus, $empresa_id){
 
            $statusAntigo = StatusHistory::where('task_id', $task)
                ->where('status_id', $status)
                ->where('empresa_id', $empresa_id)
                ->whereNull('saida')->first();

            if($statusAntigo){

                $statusAntigo->update(['saida' => now()]);

            }

            $this->registrar($usuario, $newStatus, $task, $empresa_id);
        }
    }