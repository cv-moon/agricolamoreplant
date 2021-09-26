<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table = 'creditos';
    protected $fillable = [
        'id',
        'saldo',
        'abonos',
        'dias_credito',
        'fec_limite',
        'observaciones',
        'estado'
    ];
}
