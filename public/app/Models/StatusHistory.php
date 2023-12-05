<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','empresa_id', 'task_id', 'status_id', 'saida', 'comentario'];

    protected $table = "status_history";
}
