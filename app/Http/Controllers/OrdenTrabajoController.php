<?php

namespace App\Http\Controllers;

use App\Models\AbonoOrdenTrabajo;
use App\Models\Cliente;
use App\Models\DetalleOrden;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\OrdenTrabajo;
use App\Models\PuntoEmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenTrabajoController extends Controller
{
    public function index()
    {
        $odts = OrdenTrabajo::join('clientes', 'ordenes_trabajo.cliente_id', 'clientes.id')
            ->join('users', 'ordenes_trabajo.usuario_id', 'users.id')
            ->select(
                'ordenes_trabajo.id',
                'ordenes_trabajo.num_secuencial',
                'ordenes_trabajo.val_total',
                'ordenes_trabajo.created_at',
                'clientes.nombre as cliente',
                'users.usuario'
            )
            ->orderBy('ordenes_trabajo.created_at', 'desc')
            ->get();
        return $odts;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $odt = new OrdenTrabajo();
            $odt->cliente_id = trim($request->cliente_id);
            $odt->punto_id = trim($request->punto_id);
            $odt->usuario_id = Auth::user()->id;
            $odt->num_secuencial = trim($request->num_secuencial);
            $odt->sub_sin_imp = trim($request->sub_sin_imp);
            $odt->tot_descuento = trim($request->tot_descuento);
            $odt->val_total = trim($request->val_total);
            $odt->estado = 'R';
            $odt->save();

            $detalles = $request->detalles;

            $punto = PuntoEmision::select('establecimiento_id', 'user_id')
                ->where('user_id', Auth::user()->id)
                ->first();

            foreach ($detalles as $ep => $det) {
                $detalle = new DetalleOrden();
                $detalle->orden_trabajo_id = $odt->id;
                $detalle->presentacion_id = $det['presentacion_id'];
                $detalle->det_cantidad = trim($det['det_cantidad']);
                $detalle->det_precio = trim($det['det_precio']);
                $detalle->det_descuento = $det['det_descuento'];
                $detalle->det_total = $det['det_total'];
                $detalle->save();

                $get = Inventario::select('id', 'presentacion_id', 'establecimiento_id')
                    ->where('presentacion_id', $det['presentacion_id'])
                    ->where('establecimiento_id', $punto->establecimiento_id)
                    ->first();

                $inventario = Inventario::findOrFail($get->id);
                $inventario->dis_stock = $inventario->dis_stock - $det['det_cantidad'];
                $inventario->save();

                $cliente = Cliente::select('id', 'nombre')
                    ->where('id', $odt->cliente_id)
                    ->first();

                $kardex = new Kardex();
                $kardex->inventario_id = $get->id;
                $kardex->concepto = mb_strtoupper('ORDEN: ' . $odt->num_secuencial . ' ' . $cliente->nombre);
                $kardex->cantidad = $det['det_cantidad'];
                $kardex->save();
            }

            if ($request->abono != 0) {
                $abonoOdt = new AbonoOrdenTrabajo();
                $abonoOdt->orden_trabajo_id = $odt->id;
                $abonoOdt->sec_recibo = trim($request->sec_recibo);
                $abonoOdt->val_abono = trim($request->val_abono);
                $abonoOdt->fec_abono = trim($request->fec_abono);
                $abonoOdt->observaciones = mb_strtoupper(trim($request->observaciones));
                $abonoOdt->estado = 'R';
                $abonoOdt->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function detail(Request $request)
    {
        $odt = Compra::join('proveedores', 'compras.proveedor_id', 'proveedores.id')
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
                'proveedores.raz_social as proveedor'
            )
            ->where('compras.id', $request->id)
            ->first();
        $detalles = DetalleCompra::join('presentaciones', 'detalles_compra.presentacion_id', 'presentaciones.id')
            ->join('productos', 'presentaciones.producto_id', 'productos.id')
            ->select(
                'detalles_compra.compra_id',
                'detalles_compra.cantidad',
                'detalles_compra.fec_vencimiento',
                'detalles_compra.precio',
                'detalles_compra.descuento',
                'productos.nombre as producto'
            )
            ->where('detalles_compra.compra_id', $request->id)
            ->get();
        return ['comprobante' => $odt, 'detalles' => $detalles];
    }

    public function cancel(Request $request)
    {
        try {
            DB::beginTransaction();
            $odt = Compra::findOrFail($request->id);
            $odt->estado = 0;
            $odt->save();
            $detalles = DetalleCompra::where('compra_id', '=', $request->id)
                ->get();
            foreach ($detalles as $key => $det) {
                $producto = Producto::findOrFail($det['producto_id']);
                $producto->dis_stock = $producto->dis_stock - $det['cantidad'];
                $producto->save();
            }
            if ($odt->for_pago == 'C') {
                $deuda = Deuda::findOrFail($request->id);
                $deuda->estado = 'A';
                $deuda->save();
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function getOrder()
    {
        $sec_inicial = PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
            ->select(
                'puntos_emision.id',
                'puntos_emision.pun_codigo as punto',
                'puntos_emision.sec_orden_trabajo'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->first();
        $secuencial = OrdenTrabajo::join('puntos_emision', 'ordenes_trabajo.punto_id', 'puntos_emision.id')
            ->select(
                'ordenes_trabajo.num_secuencial',
                'ordenes_trabajo.created_at'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($secuencial) {
            $consecutivo = $secuencial->num_secuencial + 1;
        } else {
            $consecutivo = $sec_inicial->sec_factura + 1;
        }

        $hoy = date('d/m/Y');
        return [
            'punto_id' => $sec_inicial->id,
            'fec_emision' => $hoy,
            'num_secuencial' => $consecutivo
        ];
    }
}
