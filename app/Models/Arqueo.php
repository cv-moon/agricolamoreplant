<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Arqueo extends Model
{
    protected $table = 'arqueos';
    protected $fillable = [
        'punto_id',
        'user_id',
        'mon_apertura',
        'fec_cierre',
        'mon_cierre',
        'tot_efectivo',
        'tot_credito',
        'tot_egreso',
        'observaciones',
        'estado'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
