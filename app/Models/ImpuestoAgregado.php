<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpuestoAgregado extends Model
{
    protected $table = 'imp_agregados';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
