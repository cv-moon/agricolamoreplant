<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Deuda;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::join('proveedores', 'compras.proveedor_id', 'proveedores.id')
            ->join('establecimientos', 'compras.establecimiento_id', 'establecimientos.id')
            ->select(
                'compras.id',
                'compras.tip_comprobante',
                'compras.num_comprobante',
                'compras.fec_emision',
                'compras.total',
                'compras.for_pago',
                'compras.estado',
                'compras.created_at',
                'proveedores.nombre as proveedor',
                'establecimientos.nom_referencia as establecimiento'
            )
            ->orderBy('compras.created_at', 'desc')
            ->get();
        return $compras;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $compra = new Compra();
            $compra->proveedor_id = trim($request->proveedor_id);
            $compra->establecimiento_id = trim($request->establecimiento_id);
            $compra->tip_comprobante = mb_strtoupper(trim($request->tip_comprobante));
            $compra->num_comprobante = trim($request->num_comprobante);
            $compra->fec_emision = trim($request->fec_emision);
            $compra->sub_0 = trim($request->sub_0);
            $compra->sub_12 = trim($request->sub_12);
            $compra->tot_desc = trim($request->tot_desc);
            $compra->total = trim($request->total);
            $compra->for_pago = mb_strtoupper(trim($request->for_pago));
            $compra->save();

            $detalles = $request->detalles;

            foreach ($detalles as $ep => $det) {
                $detalle = new DetalleCompra();
                $detalle->compra_id = $compra->id;
                $detalle->producto_id = $det['producto_id'];
                $detalle->cantidad = trim($det['cantidad']);
                if (!empty($det['fec_vencimiento'])) {
                    $detalle->fec_vencimiento = $det['fec_vencimiento'];
                }
                $detalle->precio = trim($det['precio']);
                $detalle->descuento = $det['descuento'];
                $detalle->save();

                $datos = Inventario::select('id', 'producto_id', 'establecimiento_id', 'dis_stock')
                    ->where('producto_id', $det['producto_id'])
                    ->where('establecimiento_id', $compra->establecimiento_id)
                    ->first();

                if (!empty($datos)) {
                    $inventario = Inventario::findOrFail($datos["id"]);
                    $inventario->dis_stock = $datos["dis_stock"] + trim($det['cantidad']);
                    $inventario->save();

                    $proveedor = Proveedor::select('id', 'nombre')
                        ->where('id', $request->proveedor_id)
                        ->first();

                    $kardex = new Kardex();
                    $kardex->inventario_id = $inventario->id;
                    $kardex->concepto = mb_strtoupper("COMPRA: " . $compra->tip_comprobante . " " . $compra->num_comprobante . " " . $proveedor['nombre']);
                    $kardex->cantidad = trim($det['cantidad']);
                    $kardex->save();
                }
            }
            if ($request->for_pago == "C") {
                $deuda = new Deuda();
                $deuda->id = $compra->id;
                $deuda->saldo = trim($request->saldo);
                $deuda->abonos = 0;
                $deuda->dias_credito = trim($request->dias_credito);
                $deuda->fec_limite = trim($request->fec_limite);
                $deuda->observaciones = mb_strtoupper(trim($request->observaciones));
                $deuda->estado = 'P';
                $deuda->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function detail(Request $request)
    {
        $compra = Compra::join('proveedores', 'compras.proveedor_id', '=', 'proveedores.id')
            ->select(
                'compras.id',
                'compras.tip_comprobante',
                'compras.num_comprobante',
                'compras.fec_emision',
                'compras.sub_0',
                'compras.sub_12',
                'compras.tot_desc',
                'compras.total',
                'compras.for_pago',
                'proveedores.nombre as proveedor'
            )
            ->where('compras.id', '=', $request->id)
            ->first();
        $detalles = DetalleCompra::join('productos', 'detalles_compra.producto_id', '=', 'productos.id')
            ->select(
                'detalles_compra.compra_id',
                'detalles_compra.cantidad',
                'detalles_compra.fec_vencimiento',
                'detalles_compra.precio',
                'detalles_compra.descuento',
                'productos.nombre as producto'
            )
            ->where('detalles_compra.compra_id', '=', $request->id)
            ->get();
        return ['comprobante' => $compra, 'detalles' => $detalles];
    }

    public function cancel(Request $request)
    {
        try {
            DB::beginTransaction();
            $compra = Compra::findOrFail($request->id);
            $compra->estado = 0;
            $compra->save();
            $detalles = DetalleCompra::where('compra_id', '=', $request->id)
                ->get();
            foreach ($detalles as $key => $det) {
                $producto = Producto::findOrFail($det['producto_id']);
                $producto->dis_stock = $producto->dis_stock - $det['cantidad'];
                $producto->save();
            }
            if ($compra->for_pago == 'C') {
                $deuda = Deuda::findOrFail($request->id);
                $deuda->estado = 'A';
                $deuda->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
