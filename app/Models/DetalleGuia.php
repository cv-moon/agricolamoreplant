<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleGuia extends Model
{
    protected $table = 'detalles_guia';
    protected $fillable = [
        'guia_id',
        'producto_id',
        'det_cantidad'
    ];
    public $timestamps = false;
}
