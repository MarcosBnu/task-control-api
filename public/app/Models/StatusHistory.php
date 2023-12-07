<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="StatusHistory",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="empresa_id", type="integer", example=1),
 *     @OA\Property(property="task_id", type="integer", example=1),
 *     @OA\Property(property="status_id", type="integer", example=1),
 *     @OA\Property(property="saida", type="string", format="date-time", example="2023-11-09T12:34:56Z"),
 *     @OA\Property(property="comentario", type="string", example="Comentário sobre o que foi feito na tarefa até aquele momento"),
 * )
 */

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','empresa_id', 'task_id', 'status_id', 'saida', 'comentario'];

    protected $table = "status_history";
}
