<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    protected $table = 'egresos';
    protected $fillable = [
        'arqueo_id',
        'user_id',
        'descripcion',
        'valor'
    ];
}
