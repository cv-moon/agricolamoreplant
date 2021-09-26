<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = 'detalles_factura';
    protected $fillable = [
        'factura_id',
        'producto_id',
        'det_cantidad',
        'det_precio',
        'det_descuento',
        'det_total',
        'det_campo'
    ];
    public $timestamps = false;
}
