<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $table = 'comprobantes';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
