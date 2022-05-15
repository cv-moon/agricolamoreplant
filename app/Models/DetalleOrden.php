<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    protected $table = 'detalles_orden';
    protected $fillable = [
        'orden_trabajo_id',
        'presentacion_id',
        'det_cantidad',
        'det_precio',
        'det_descuento',
        'det_total'
    ];
    public $timestamps = false;
}
