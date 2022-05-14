<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';
    protected $fillable = [
        'cliente_id',
        'punto_id',
        'usuario_id',
        'for_pago_id',
        'num_secuencial',
        'sub_sin_imp',
        'tot_descuento',
        'val_total',
        'for_pago',
        'estado'
    ];
}
