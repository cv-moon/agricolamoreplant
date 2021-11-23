<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpuestoRetencion extends Model
{
    protected $table = 'impuestos_retencion';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
