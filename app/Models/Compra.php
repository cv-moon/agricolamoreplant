<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable = [
        'proveedor_id',
        'establecimiento_id',
        'tip_comprobante',
        'num_comprobante',
        'fec_emision',
        'sub_0',
        'sub_12',
        'tot_desc',
        'total',
        'for_pago',
        'estado'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
