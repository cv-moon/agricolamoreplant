<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAmbiente extends Model
{
    protected $table = 'tip_ambientes';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
