<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventarios';
    protected $fillable = [
        'presentacion_id',
        'establecimiento_id',
        'dis_stock',
        'min_stock',
        'estado'
    ];
}
