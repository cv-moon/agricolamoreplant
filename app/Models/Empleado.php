<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = [
        'nombres',
        'apellidos',
        'tip_identificacion',
        'num_identificacion',
        'direccion',
        'telefonos',
        'salario',
        'fec_ingreso',
        'fec_salida'
    ];
}
