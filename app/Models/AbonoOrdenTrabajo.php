<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbonoOrdenTrabajo extends Model
{
    protected $table = 'abonos_odt';
    protected $fillable = [
        'orden_trabajo_id',
        'sec_recibo',
        'val_abono',
        'fec_abono',
        'observaciones',
        'estado'
    ];
    public $timestamps = false;
}
