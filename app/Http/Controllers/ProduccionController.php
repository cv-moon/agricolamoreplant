<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    public function index()
    {
        $producciones = Produccion::join('productos', 'producciones.producto_id', 'productos.id')
            ->select(
                'producciones.id',
                'producciones.fec_ingreso',
                'producciones.cant_ingreso',
                'producciones.por_mortalidad',
                'producciones.cant_salida',
                'producciones.estado',
                'productos.nombre'
            )
            ->orderBy('producciones.fec_ingreso')
            ->get();
        return $producciones;
    }

    public function store(Request $request)
    {

        $produccion = new Produccion();
        $produccion->producto_id = $request->producto_id;
        $produccion->fec_ingreso = $request->fec_ingreso;
        $produccion->cant_ingreso = $request->cant_ingreso;
        $produccion->estado = 'EP';
        $produccion->save();
    }

    public function update(Request $request)
    {
        $produccion = Produccion::findOrFail($request->id);
        $produccion->producto_id = $request->producto_id;
        $produccion->fec_ingreso = $request->fec_ingreso;
        $produccion->cant_ingreso = $request->cant_ingreso;
        $produccion->save();
    }

    public function detail(Request $request)
    {
        $produccion = Produccion::join('productos', 'producciones.producto_id', 'productos.id')
            ->select(
                'producciones.id',
                'producciones.producto_id',
                'producciones.fec_ingreso',
                'producciones.cant_ingreso',
                'producciones.estado',
                'productos.nombre as planta'
            )
            ->where('producciones.id', $request->id)
            ->first();
        return $produccion;
    }

    public function finished(Request $request)
    {
        $produccion = Produccion::findOrFail($request->id);
        $produccion->por_mortalidad = $request->por_mortalidad;
        $produccion->cant_salida = $request->cant_salida;
        $produccion->estado = 'PF';
        $produccion->save();
    }
}
