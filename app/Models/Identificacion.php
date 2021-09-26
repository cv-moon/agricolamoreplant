<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identificacion extends Model
{
    protected $table = 'identificaciones';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
