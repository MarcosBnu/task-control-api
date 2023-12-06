<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     title="Users",
 *     description="Modelo de Users",
 *     @OA\Property(property="id", type="integer", description="ID do Usuário", example=1),
 *     @OA\Property(property="empresa_id", type="integer", description="ID da Empresa associada ao Usuário", example=1),
 *     @OA\Property(property="name", type="string", description="Nome do Usuário", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", description="E-mail do Usuário", example="john@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", description="Data de verificação do e-mail", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="password", type="string", description="Senha do Usuário (hash)", example="********"),
 *     @OA\Property(property="tipoUsuario", type="string", enum={"admin", "lider", "rh", "expectador", "operacional"}, description="Tipo de Usuário"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização", example="2023-01-01T12:00:00Z"),
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'empresa_id',
        'tipoUsuario'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresas::class, 'empresa_id');
    }

    public function statushistory(){

        return $this->hasMany(StatusHistory::class, 'user_id');

    }

}
