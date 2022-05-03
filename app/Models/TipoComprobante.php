<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table = 'tip_comprobantes';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
