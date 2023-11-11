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

        public final function registrar($usuario, $status, $task){

            StatusHistory::create([
                'user_id'   => $usuario,
                'task_id'   => $task,
                'status_id' => $status
            ]);
        }

        public final function saida($usuario, $task, $status, $newStatus){

            $statusAntigo = StatusHistory::where('user_id', $usuario)->where('task_id', $task)->where('status_id', $status)->whereNull('saida')->first();

            $statusAntigo->update(['saida' => now()]);

            $this->registrar($usuario, $newStatus, $task);
        }
    }