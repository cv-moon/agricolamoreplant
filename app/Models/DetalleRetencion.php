<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleRetencion extends Model
{
    protected $table = 'detalles_retencion';
    protected $fillable =
    [
        'retencion_id',
        'compra_id',
        'comprobante_id',
        'tarifas_retencion_id',
        'num_comprobante',
        'fec_emi_comprobante',
        'bas_imponible',
        'val_retenido'
    ];

    public $timestamps = false;
}
