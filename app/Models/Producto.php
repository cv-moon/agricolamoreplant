<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'categoria_id',
        'unidad_id',
        'tarifa_id',
        'nombre',
        'cod_principal',
        'cod_auxiliar',
        'composicion',
        'pre_venta',
        'por_descuento'
    ];
}
