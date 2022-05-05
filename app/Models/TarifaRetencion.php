<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifaRetencion extends Model
{
    protected $table = 'tar_retenciones';
    protected $fillable = [
        'imp_retencion_id',
        'nombre',
        'codigo',
        'valor'
    ];
    public $timestamps = false;
}
