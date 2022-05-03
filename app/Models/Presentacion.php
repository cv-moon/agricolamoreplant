<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    protected $table = 'presentaciones';
    protected $fillable = [
        'producto_id',
        'unidade_id',
        'cod_principal',
        'cod_auxiliar',
        'presentacion',
        'pre_venta',
        'estado'
    ];
}
