<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        $productos = Presentacion::join('productos', 'presentaciones.producto_id', 'producto.id')
            ->join('unidades', 'presentaciones.unidad_id', 'unidades.id')
            ->join('categorias', 'productos.categoria_id', 'categorias.id')
            ->join('tar_agregados', 'productos.tar_agregado_id', 'tar_agregados.id')
            ->select(
                'presentaciones.id',
                'presentaciones.cod_principal',
                'presentaciones.pre_venta',
                'presentaciones.presentacion',
                'presentaciones.estado',
                'productos.nombre',
                'productos.composicion',
                'productos.por_descuento',
                'categorias.nombre as categoria',
                'unidades.sigla as unidad',
                'tar_agregados.valor as impuesto',
            )
            ->orderBy('categorias.nombre', 'asc')
            ->get();
        return $productos;
    }
}
