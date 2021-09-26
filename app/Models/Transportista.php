<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    protected $table = 'transportistas';
    protected $fillable = [
        'identificacion_id',
        'nombre',
        'num_identificacion',
        'placa',
        'direccion',
        'email',
        'telefonos'
    ];
}
