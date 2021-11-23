<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $table = 'kardexs';
    protected $fillable = [
        'inventario_id',
        'concepto',
        'cantidad'
    ];
    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
