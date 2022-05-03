<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpuestoRetencion extends Model
{
    protected $table = 'imp_retenciones';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
