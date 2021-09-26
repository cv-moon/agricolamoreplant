<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $fillable = [
        'identificacion_id',
        'nombre',
        'num_identificacion',
        'direccion',
        'telefonos',
        'email',
        'descuento',
        'lim_credito'
    ];
}
