<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbonoCredito extends Model
{
    protected $table = 'abonos_credito';
    protected $fillable = [
        'credito_id',
        'val_abono',
        'fec_abono',
        'observaciones'
    ];
    public $timestamps = false;
}
