<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEstado extends Model
{
    protected $table = 'tip_estados';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
