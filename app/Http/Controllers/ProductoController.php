<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\PuntoEmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::join('categorias', 'productos.categoria_id', 'categorias.id')
            ->join('unidades', 'productos.unidad_id', 'unidades.id')
            ->join('tarifas', 'productos.tarifa_id', 'tarifas.id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.cod_principal',
                'productos.composicion',
                'productos.pre_venta',
                'productos.por_descuento',
                'categorias.nombre as categoria',
                'unidades.sigla as unidad',
                'tarifas.valor as impuesto'
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
            $producto->unidad_id = trim($request->unidad_id);
            $producto->tarifa_id = trim($request->tarifa_id);
            $producto->cod_principal = mb_strtoupper(trim($request->cod_principal));
            $producto->cod_auxiliar = mb_strtoupper(trim($request->cod_auxiliar));
            $producto->nombre = mb_strtoupper(trim($request->nombre));
            $producto->composicion = mb_strtoupper(trim($request->composicion));
            $producto->pre_venta = trim($request->pre_venta);
            $producto->por_descuento = trim($request->por_descuento);
            $producto->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $producto = Producto::findOrFail($request->id);
            $producto->categoria_id = trim($request->categoria_id);
            $producto->unidad_id = trim($request->unidad_id);
            $producto->tarifa_id = trim($request->tarifa_id);
            $producto->cod_principal = mb_strtoupper(trim($request->cod_principal));
            $producto->cod_auxiliar = mb_strtoupper(trim($request->cod_auxiliar));
            $producto->nombre = mb_strtoupper(trim($request->nombre));
            $producto->composicion = mb_strtoupper(trim($request->composicion));
            $producto->pre_venta = trim($request->pre_venta);
            $producto->por_descuento = trim($request->por_descuento);
            $producto->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
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
            ->join('tarifas', 'productos.tarifa_id', 'tarifas.id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.cod_principal',
                'productos.pre_venta',
                'inventarios.establecimiento_id',
                'inventarios.dis_stock',
                'inventarios.min_stock',
                'inventarios.estado',
                'tarifas.valor as impuesto'
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
        $productos = Producto::join('tarifas', 'productos.tarifa_id', 'tarifas.id')
            ->join('inventarios', 'productos.id', 'inventarios.producto_id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.pre_venta',
                'productos.por_descuento',
                'productos.composicion',
                'tarifas.nombre as impuesto',
                'tarifas.valor',
                'tarifas.codigo',
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
