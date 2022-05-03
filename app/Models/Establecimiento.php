<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    protected $table = 'establecimientos';
    protected $fillable = [
        'empresa_id',
        'user_id',
        'est_codigo',
        'nom_comercial',
        'nom_referencia',
        'direccion',
        'telefonos',
        'estado'
    ];
}
