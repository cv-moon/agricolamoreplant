<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'tip_identificacion_id',
        'raz_social',
        'num_identificacion',
        'localidad',
        'direccion',
        'email',
        'tel_uno',
        'tel_dos'
    ];
}
