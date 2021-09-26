<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Kardex;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    public function detail(Request $request)
    {
        $kardex = Kardex::select(
            'inventario_id',
            'created_at',
            'concepto',
            'cantidad'
        )
            ->where('inventario_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $datos = Inventario::join('productos', 'inventarios.producto_id', 'productos.id')
            ->join('establecimientos', 'inventarios.establecimiento_id', 'establecimientos.id')
            ->select(
                'inventarios.id',
                'inventarios.min_stock',
                'inventarios.dis_stock',
                'inventarios.estado',
                'productos.nombre',
                'productos.cod_principal',
                'productos.pre_venta',
                'establecimientos.nom_comercial',
                'establecimientos.nom_referencia'
            )
            ->where('inventarios.id', $request->id)
            ->first();

        return ['kardex' => $kardex, 'datos' => $datos];
    }
}
