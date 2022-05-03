<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $fillable = [
        'tip_ambiente_id',
        'raz_social',
        'ruc',
        'direccion',
        'logo',
        'cont_resolucion',
        'obli_contabilidad',
        'tip_regimen',
        'age_retencion',
        'tip_emision',
        'firma',
        'fir_clave',
        'fir_vencimiento'
    ];
}
