<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoEmision extends Model
{
    protected $table = 'puntos_emision';
    protected $fillable = [
        'establecimiento_id',
        'user_id',
        'pun_codigo',
        'nombre',
        'sec_factura',
        'sec_retencion',
        'sec_gui_remision',
        'sec_orden_trabajo',
        'sec_recibo_cobro',
        'estado'
    ];
}
