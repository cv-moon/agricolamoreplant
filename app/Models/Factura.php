<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';
    protected $fillable = [
        'cliente_id',
        'punto_id',
        'usuario_id',
        'for_pago_id',
        'fec_emision',
        'fec_autorizacion',
        'num_secuencial',
        'tip_emision',
        'tip_ambiente',
        'cla_acceso',
        'num_autorizacion',
        'sub_sin_imp',
        'sub_iva',
        'sub_0',
        'sub_no_iva',
        'sub_exento',
        'tot_descuento',
        'val_ice',
        'val_irbpnr',
        'val_iva',
        'val_propina',
        'val_total',
        'for_pago',
        'respuesta',
        'estado'
    ];
}
