<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaRetencion extends Model
{
    protected $table = 'tarifas_retencion';
    protected $fillable = [
        'impuesto_id',
        'nombre',
        'codigo',
        'valor'
    ];
    public $timestamps = false;
}
