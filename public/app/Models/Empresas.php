<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
