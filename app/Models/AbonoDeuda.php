<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbonoDeuda extends Model
{
    protected $table = 'abonos_deuda';
    protected $fillable = [
        'deuda_id',
        'val_abono',
        'fec_abono',
        'obs_abono'
    ];
}
