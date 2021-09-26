<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    protected $table = 'impuestos';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
