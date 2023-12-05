<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'empresa_id', 'status_id', 'descricao', 'finalizada', 'dataFinalizado', 'dataDeEntrega'];

    public function statushistory(){

        return $this->hasMany(StatusHistory::class, 'task_id');

    }
}
