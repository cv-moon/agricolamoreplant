<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleRol extends Model
{
    protected $table = 'detalles_rol';
    protected $fillable = [
        'for_pago',
        'val_pago',
        'observaciones'
    ];
}
