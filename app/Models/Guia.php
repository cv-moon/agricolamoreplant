<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    protected $table = 'guias';
    protected $fillable = [
        'factura_id',
        'usuario_id',
        'punto_id',
        'transportista_id',
        'fec_emision',
        'fec_autorizacion',
        'num_secuencial',
        'tip_emision',
        'tip_ambiente',
        'cla_acceso',
        'num_autorizacion',
        'fec_inicio',
        'fec_fin',
        'des_nombre',
        'des_direccion',
        'des_identificacion',
        'motivo',
        'ruta',
        'observaciones',
        'respuesta',
        'estado'
    ];
}
