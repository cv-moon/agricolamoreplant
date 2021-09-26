<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    protected $table = 'deudas';
    protected $fillable = [
        'saldo',
        'abonos',
        'dias_credito',
        'fec_limite',
        'observaciones',
        'estado'
    ];
}
