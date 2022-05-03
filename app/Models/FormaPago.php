<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'for_pagos';
    protected $fillable = [
        'nombre',
        'codigo'
    ];
    public $timestamps = false;
}
