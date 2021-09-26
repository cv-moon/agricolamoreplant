<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $table = 'kardexs';
    protected $fillable = [
        'inventario_id',
        'concepto',
        'cantidad'
    ];
}
