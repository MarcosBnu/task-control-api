<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cnpj'];

    public function tasks(){

        return $this->hasMany(Task::class);

    }

    public function status(){

        return $this->hasMany(Status::class);

    }
}
