<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use Illuminate\Http\Request;

class OrdenTrabajoController extends Controller
{
    public function index()
    {
        $odts = OrdenTrabajo::join('clientes', 'ordenes_trabajo.cliente_id', 'clientes.id')
            ->join('users', 'ordenes_trabajo.usuario_id', 'users.id')
            ->join('for_pagos', 'ordenes_trabajo.for_pago_id', 'for_pagos.id')
            ->select(
                'ordenes_trabajo.id',
                'ordenes_trabajo.num_secuencial',
                'ordenes_trabajo.val_total',
                'users.usuario',
                'ordenes_trabajo.id',
                'ordenes_trabajo.id',
            )
            ->get();
        return $odts;
    }
}
