<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Kardex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $inventarios = Inventario::join('productos', 'inventarios.producto_id', 'productos.id')
            ->join('establecimientos', 'inventarios.establecimiento_id', 'establecimientos.id')
            ->join('unidades', 'productos.unidad_id', 'unidades.id')
            ->select(
                'inventarios.id',
                'inventarios.establecimiento_id',
                'inventarios.dis_stock',
                'inventarios.min_stock',
                'inventarios.estado',
                'productos.cod_principal',
                'productos.nombre',
                'unidades.sigla as unidad',
                'establecimientos.nom_referencia'
            )
            ->where('inventarios.establecimiento_id', $request->establecimiento)
            ->orderBy('productos.cod_principal', 'asc')
            ->get();
        return $inventarios;
    }

    public function store(Request $request)
    {
        $products = $request->productos;
        try {
            DB::beginTransaction();
            foreach ($products as $producto => $pro) {
                if (key_exists('check', $pro)) {
                    $inventario = new Inventario();
                    $inventario->producto_id = trim($pro['id']);
                    $inventario->establecimiento_id = trim($request->establecimiento_id);
                    $inventario->min_stock = trim($pro['min_stock']);
                    $inventario->dis_stock = trim($pro['dis_stock']);
                    $inventario->save();
                    $kardex = new Kardex();
                    $kardex->inventario_id = $inventario->id;
                    $kardex->concepto = mb_strtoupper(trim('INGRESO: STOCK INICIAL'));
                    $kardex->cantidad = trim($inventario->dis_stock);
                    $kardex->save();
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function activate(Request $request)
    {
        $inventario = Inventario::findOrFail($request->id);
        $inventario->estado = 1;
        $inventario->save();
    }

    public function deactivate(Request $request)
    {
        $inventario = Inventario::findOrFail($request->id);
        $inventario->estado = 0;
        $inventario->save();
    }
}
