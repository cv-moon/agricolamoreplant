<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProveedorContacto extends Model
{
    protected $table = 'prov_contactos';
    protected $fillable = [
        'proveedor_id',
        'nombre',
        'email',
        'tel_uno',
        'tel_dos',
        'cel_uno',
        'cel_dos'
    ];
}
