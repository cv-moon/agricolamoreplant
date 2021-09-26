<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalles_compra';
    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'fec_vencimiento',
        'precio',
        'descuento'
    ];
    public $timestamps = false;
}
