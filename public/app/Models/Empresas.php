<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Empresas",
 *     description="Modelo de Empresas",
 *     @OA\Property(property="id", type="integer", description="ID da Empresa", example=1),
 *     @OA\Property(property="name", type="string", description="Nome da Empresa", example="Minha Empresa"),
 *     @OA\Property(property="cnpj", type="string", description="CNPJ da Empresa", example="12.345.678/0001-90"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização", example="2023-01-01T12:00:00Z"),
 * )
 */

class Empresas extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'cnpj'];

    public function tasks(){

        return $this->hasMany(Task::class, 'empresa_id');

    }

    public function status(){

        return $this->hasMany(Status::class, 'empresa_id');

    }

    public function users(){

        return $this->hasMany(User::class, 'empresa_id');

    }

    public function statushistory(){

        return $this->hasMany(StatusHistory::class, 'empresa_id');

    }
}
