<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaAgregado extends Model
{
    protected $table = 'tar_agregados';
    protected $fillable = [
        'imp_agregado_id',
        'nombre',
        'codigo',
        'valor'
    ];
    public $timestamps = false;
}
