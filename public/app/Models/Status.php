<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Status",
 *     description="Modelo de Status",
 *     @OA\Property(property="id", type="integer", description="ID do Status", example=1),
 *     @OA\Property(property="empresa_id", type="integer", description="ID da Empresa associada ao Status", example=1),
 *     @OA\Property(property="nome", type="string", description="Nome do Status", example="Em Progresso"),
 *     @OA\Property(property="descricao", type="string", description="Descrição do Status", example="Tarefa em andamento"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização", example="2023-01-01T12:00:00Z"),
 * )
 */
class Status extends Model
{
    use HasFactory;

    protected $fillable = ['empresa_id', 'nome', 'descricao'];

    protected $table = 'status';

}
