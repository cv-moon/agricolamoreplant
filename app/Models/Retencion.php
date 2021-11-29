<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    protected $table = 'retenciones';
    protected $fillable = [
        'punto_id',
        'usuario_id',
        'proveedor_id',
        'fec_emision',
        'fec_autorizacion',
        'num_secuencial',
        'tip_emision',
        'tip_ambiente',
        'cla_acceso',
        'num_autorizacion',
        'eje_fiscal',
        'tot_retenido',
        'estado',
        'respuesta'
    ];
}
