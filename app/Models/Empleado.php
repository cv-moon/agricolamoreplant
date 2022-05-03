<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = [
        'tip_identificacion_id',
        'nom_primero',
        'nom_segundo',
        'ape_paterno',
        'ape_materno',
        'num_identificacion',
        'direccion',
        'telefonos',
        'salario',
        'fec_ingreso',
        'fec_salida'
    ];
}
