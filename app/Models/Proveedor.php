<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = [
        'nombre',
        'tip_identificacion',
        'num_identificacion',
        'direccion',
        'email',
        'telefonos',
        'nom_contacto',
        'tel_contacto'
    ];
}
