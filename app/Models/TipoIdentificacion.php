<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoIdentificacion extends Model
{
    protected $table = 'tip_identificaciones';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
