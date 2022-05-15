<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\PuntoEmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Presentacion::join('productos', 'presentaciones.producto_id', 'productos.id')
            ->join('categorias', 'productos.categoria_id', 'categorias.id')
            ->join('tar_agregados', 'productos.tar_agregado_id', 'tar_agregados.id')
            ->join('unidades', 'presentaciones.unidad_id', 'unidades.id')
            ->select(
                'presentaciones.id',
                'presentaciones.cod_principal',
                'presentaciones.presentacion',
                'presentaciones.pre_venta',
                'productos.nombre',
                'productos.por_descuento',
                'unidades.sigla',
                'categorias.nombre as categoria',
                'tar_agregados.valor as impuesto'
            )
            ->orderBy('categorias.nombre', 'asc')
            ->get();
        return $productos;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $producto = new Producto();
            $producto->categoria_id = trim($request->categoria_id);
            $producto->tar_agregado_id = trim($request->tar_agregado_id);
            $producto->nombre = mb_strtoupper(trim($request->nombre));
            $producto->composicion = mb_strtoupper(trim($request->composicion));
            $producto->pre_compra = trim($request->pre_compra);
            $producto->por_descuento = trim($request->por_descuento);
            $producto->mar_utilidad = trim($request->mar_utilidad);
            $producto->save();

            foreach ($request->presentaciones as $ep => $det) {
                $presentacion = new Presentacion();
                $presentacion->producto_id = $producto->id;
                $presentacion->unidad_id = trim($det['unidad_id']);
                $presentacion->cod_principal = mb_strtoupper(trim($det['cod_principal']));
                $presentacion->cod_auxiliar = mb_strtoupper(trim($det['cod_auxiliar']));
                $presentacion->presentacion = trim($det['presentacion']);
                $presentacion->pre_venta = trim($det['pre_venta']);
                $presentacion->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $presentacion = Presentacion::findOrFail($request->id);
            $presentacion->unidad_id = trim($request->unidad_id);
            $presentacion->cod_principal = mb_strtoupper(trim($request->cod_principal));
            $presentacion->cod_auxiliar = mb_strtoupper(trim($request->cod_auxiliar));
            $presentacion->pre_venta = trim($request->pre_venta);
            $presentacion->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function detail(Request $request)
    {
        $producto = Presentacion::join('productos', 'presentaciones.producto_id', 'productos.id')
            ->select(
                'presentaciones.id',
                'presentaciones.unidad_id',
                'presentaciones.cod_principal',
                'presentaciones.cod_auxiliar',
                'presentaciones.presentacion',
                'presentaciones.pre_venta',
                'productos.categoria_id',
                'productos.tar_agregado_id',
                'productos.nombre',
                'productos.composicion',
                'productos.pre_compra',
                'productos.por_descuento',
                'productos.mar_utilidad',
            )
            ->where('presentaciones.id', $request->id)
            ->first();
        return $producto;
    }

    public function validateName(Request $request)
    {
        $existe = Producto::select(
            'nombre'
        )
            ->where('nombre', 'like', '%' . $request->q . '%')
            ->first();
        if ($existe) {
            return response()->json(['producto' => 'Existe']);
        } else {
            return response()->json(['producto' => 'No Existe']);
        }
    }

    public function notRegistered(Request $request)
    {
        if ($request->establecimiento != 0) {
            $productos = DB::select("SELECT p.id, p.presentacion, pr.nombre, u.sigla FROM presentaciones p INNER JOIN productos pr on p.producto_id = pr.id INNER JOIN unidades u ON u.id=p.unidad_id WHERE NOT EXISTS(SELECT i.presentacion_id FROM inventarios i WHERE p.id = i.presentacion_id and i.establecimiento_id=" . $request->establecimiento . ")");
            return $productos;
        }
    }

    public function productForBuy()
    {
        $productos = Producto::select(
            'productos.id',
            'productos.nombre'
        )
            ->orderBy('productos.nombre', 'asc')
            ->get();
        return $productos;
    }

    public function presentationsBuy(Request $request)
    {
        $productos = Inventario::join('establecimientos', 'inventarios.establecimiento_id', 'establecimientos.id')
            ->join('presentaciones', 'inventarios.presentacion_id', 'presentaciones.id')
            ->join('productos', 'presentaciones.producto_id', 'productos.id')
            ->join('tar_agregados', 'productos.tar_agregado_id', 'tar_agregados.id')
            ->join('unidades', 'presentaciones.unidad_id', 'unidades.id')
            ->select(
                'presentaciones.id',
                'presentaciones.presentacion',
                'presentaciones.producto_id',
                'productos.nombre',
                'inventarios.establecimiento_id',
                'inventarios.estado',
                'tar_agregados.valor as impuesto',
                'unidades.sigla'
            )
            ->where('inventarios.establecimiento_id', $request->e)
            ->where('presentaciones.producto_id', $request->q)
            ->orderBy('productos.nombre', 'asc')
            ->get();
        return $productos;
    }

    public function plants()
    {
        $plantas = Producto::join('categorias', 'productos.categoria_id', 'categorias.id')
            ->select(
                'productos.id',
                'productos.nombre'
            )
            ->where('categorias.nombre', 'like', '%plant%')
            ->get();
        return $plantas;
    }

    public function forSale()
    {
        $productos = Producto::select(
            'productos.id',
            'productos.nombre'
        )
            ->orderBy('productos.nombre', 'asc')
            ->get();
        return $productos;
    }

    public function presentationsSale(Request $request)
    {
        $punto = PuntoEmision::select('establecimiento_id', 'user_id')
            ->where('user_id', Auth::user()->id)
            ->first();
        $productos = Inventario::join('establecimientos', 'inventarios.establecimiento_id', 'establecimientos.id')
            ->join('presentaciones', 'inventarios.presentacion_id', 'presentaciones.id')
            ->join('productos', 'presentaciones.producto_id', 'productos.id')
            ->join('tar_agregados', 'productos.tar_agregado_id', 'tar_agregados.id')
            ->join('unidades', 'presentaciones.unidad_id', 'unidades.id')
            ->select(
                'presentaciones.id',
                'presentaciones.cod_principal',
                'presentaciones.presentacion',
                'presentaciones.pre_venta',
                'productos.nombre',
                'productos.por_descuento',
                'inventarios.establecimiento_id',
                'inventarios.min_stock',
                'inventarios.dis_stock',
                'inventarios.estado',
                'tar_agregados.nombre as impuesto',
                'tar_agregados.valor',
                'tar_agregados.codigo',
                'unidades.sigla'
            )
            ->where('inventarios.establecimiento_id', $punto->establecimiento_id)
            ->where('presentaciones.producto_id', $request->q)
            ->where('inventarios.dis_stock', '>', 0)
            ->get();
        return $productos;
    }

    public function counter(Request $request)
    {
        $conteo = Producto::where('categoria_id', $request->q)
            ->count();
        $cont = str_pad($conteo + 1, 12, "0", STR_PAD_LEFT);
        return $cont;
    }
}
