<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifas';
    protected $fillable = [
        'impuesto_id',
        'nombre',
        'codigo',
        'valor'
    ];
    public $timestamps = false;
}
