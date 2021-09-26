<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = 'producciones';
    protected $fillable = [
        'producto_id',
        'fec_ingreso',
        'cant_ingreso',
        'por_mortalidad',
        'cant_salida',
        'estado'
    ];
}
