<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Tasks",
 *     description="Modelo de Tarefa",
 *     @OA\Property(property="id", type="integer", description="ID da Tarefa", example=1),
 *     @OA\Property(property="nome", type="string", description="Nome da Tarefa", example="Minha Tarefa"),
 *     @OA\Property(property="empresa_id", type="integer", description="ID da Empresa associada à Tarefa", example=1),
 *     @OA\Property(property="status_id", type="integer", example=1),
 *     @OA\Property(property="descricao", type="string", description="Descrição da Tarefa", example="Descrição da minha tarefa"),
 *     @OA\Property(property="finalizada", type="boolean", description="Indica se a Tarefa está finalizada", example=false),
 *     @OA\Property(property="dataFinalizado", type="string", format="date-time", nullable=true, description="Data em que a Tarefa foi finalizada", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="dataDeEntrega", type="string", format="date-time", nullable=true, description="Data prevista para a entrega da Tarefa", example="2023-01-10T12:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização", example="2023-01-01T12:00:00Z"),
 * )
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ['status_id', 'nome', 'empresa_id', 'descricao', 'finalizada', 'dataFinalizado', 'dataDeEntrega'];

    public function statushistory(){

        return $this->hasMany(StatusHistory::class, 'task_id');

    }
}
