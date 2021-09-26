<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoEmision extends Model
{
    protected $table = 'puntos_emision';
    protected $fillable = [
        'establecimiento_id',
        'user_id',
        'codigo',
        'nombre',
        'sec_factura',
        'sec_liq_compras',
        'sec_not_credito',
        'sec_not_debito',
        'sec_gui_remision',
        'sec_retencion',
        'sec_recibo',
        'estado'
    ];
}
