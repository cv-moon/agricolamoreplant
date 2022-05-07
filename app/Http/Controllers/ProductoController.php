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
            $producto = Producto::findOrFail($request->id);
            $producto->categoria_id = trim($request->categoria_id);
            $producto->tar_agregado_id = trim($request->tar_agregado_id);
            $producto->nombre = mb_strtoupper(trim($request->nombre));
            $producto->composicion = mb_strtoupper(trim($request->composicion));
            $producto->pre_venta = trim($request->pre_venta);
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

    public function detail(Request $request)
    {
        $producto = Producto::select(
            'id',
            'categoria_id',
            'unidad_id',
            'tarifa_id',
            'cod_principal',
            'cod_auxiliar',
            'nombre',
            'composicion',
            'pre_venta',
            'por_descuento'
        )
            ->where('id', $request->id)
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
            $productos = DB::select("SELECT p.id, p.nombre FROM productos p WHERE NOT EXISTS(SELECT i.producto_id FROM inventarios i WHERE p.id = i.producto_id and i.establecimiento_id=" . $request->establecimiento . ")");
            return $productos;
        }
    }

    public function productForEstablishment(Request $request)
    {
        $productos = Inventario::join('productos', 'inventarios.producto_id', 'productos.id')
            ->join('establecimientos', 'inventarios.establecimiento_id', 'establecimientos.id')
            ->join('tar_agregados', 'productos.tarifa_id', 'tar_agregados.id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.cod_principal',
                'productos.pre_venta',
                'inventarios.establecimiento_id',
                'inventarios.dis_stock',
                'inventarios.min_stock',
                'inventarios.estado',
                'tar_agregados.valor as impuesto'
            )
            ->where('inventarios.establecimiento_id', $request->establecimiento)
            ->orderBy('productos.cod_principal', 'asc')
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
        $punto = PuntoEmision::select('establecimiento_id', 'user_id')
            ->where('user_id', Auth::user()->id)
            ->first();
        $productos = Producto::join('tar_agregados', 'productos.tarifa_id', 'tar_agregados.id')
            ->join('inventarios', 'productos.id', 'inventarios.producto_id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.pre_venta',
                'productos.por_descuento',
                'productos.composicion',
                'tar_agregados.nombre as impuesto',
                'tar_agregados.valor',
                'tar_agregados.codigo',
                'inventarios.establecimiento_id',
                'inventarios.min_stock',
                'inventarios.dis_stock'
            )
            ->where('inventarios.establecimiento_id', $punto->establecimiento_id)
            ->where('inventarios.dis_stock', '>', 0)
            ->orderBy('productos.nombre')
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
