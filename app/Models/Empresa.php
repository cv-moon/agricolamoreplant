<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $fillable = [
        'raz_social',
        'ruc',
        'direccion',
        'telefonos',
        'url',
        'logo',
        'cont_resolucion',
        'obli_contabilidad',
        'reg_microempresa',
        'age_retencion',
        'firma',
        'fir_clave',
        'fir_vencimiento',
        'tip_ambiente',
        'tip_emision',
        'corr_servidor',
        'corr_puerto',
        'corr_seguridad',
        'corr_autenticacion',
        'corr_usuario',
        'corr_password'
    ];
}
