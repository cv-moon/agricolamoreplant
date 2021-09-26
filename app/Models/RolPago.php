<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPago extends Model
{
    protected $table = 'roles_pago';
    protected $fillable = [
        'mes_id',
        'user_id',
        'ven_mensual',
        'por_venta',
        'tot_recibir',
        'estado'
    ];
}
