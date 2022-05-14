<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Credito;
use App\Models\DetalleFactura;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\PuntoEmision;
use Carbon\Carbon;
use generarPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use sendEmail;

include 'class/generarPDF.php';

class FacturaController extends Controller
{
    public function index()
    {
        $mes = date("m");
        if (Auth::user()->rol_id == 1) {
            $facturas = Factura::join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->join('users', 'facturas.usuario_id', 'users.id')
                ->select(
                    'facturas.id',
                    'facturas.fec_emision',
                    'facturas.num_secuencial',
                    'facturas.val_total',
                    'facturas.cla_acceso',
                    'facturas.for_pago',
                    'facturas.respuesta',
                    'clientes.nombre as cliente',
                    'clientes.num_identificacion',
                    'establecimientos.est_codigo as establecimiento',
                    'puntos_emision.pun_codigo as punto',
                    'users.usuario'
                )
                ->whereMonth('facturas.fec_emision', $mes)
                ->orderBy('facturas.created_at', 'desc')
                ->get();
        }
        if (Auth::user()->rol_id == 2) {
            $facturas = Factura::join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->join('users', 'facturas.usuario_id', 'users.id')
                ->select(
                    'facturas.id',
                    'facturas.usuario_id',
                    'facturas.fec_emision',
                    'facturas.num_secuencial',
                    'facturas.val_total',
                    'facturas.cla_acceso',
                    'facturas.for_pago',
                    'facturas.estado',
                    'clientes.nombre as cliente',
                    'clientes.num_identificacion',
                    'establecimientos.est_codigo as establecimiento',
                    'puntos_emision.pun_codigo as punto',
                    'users.usuario'
                )
                ->where('facturas.usuario_id', Auth::user()->id)
                ->whereMonth('facturas.fec_emision', $mes)
                ->orderBy('facturas.num_secuencial', 'desc')
                ->get();
        }
        return $facturas;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $factura = new Factura();
            $factura->cliente_id = $request->cliente_id;
            $factura->punto_id = $request->punto_id;
            $factura->usuario_id = Auth::user()->id;
            $factura->for_pago_id = $request->for_pago_id;
            $factura->tip_ambiente = $request->tip_ambiente;
            $factura->tip_emision = 1;
            $factura->fec_emision = date('Y-m-d');
            $factura->fec_autorizacion = date('Y-m-d');
            $factura->num_secuencial = $request->num_secuencial;
            $factura->cla_acceso = $request->cla_acceso;
            $factura->num_autorizacion = $request->cla_acceso;
            $factura->sub_sin_imp = $request->sub_sin_imp;
            $factura->sub_iva = $request->sub_iva;
            $factura->sub_0 = $request->sub_0;
            $factura->sub_no_iva = $request->sub_no_iva;
            $factura->sub_exento = $request->sub_exento;
            $factura->tot_descuento = $request->tot_descuento;
            $factura->val_ice = $request->val_ice;
            $factura->val_irbpnr = $request->val_irbpnr;
            $factura->val_iva = $request->val_iva;
            $factura->val_propina = $request->val_propina;
            $factura->val_total = $request->val_total;
            $factura->for_pago = $request->for_pago;
            $factura->estado = 'R';
            $factura->save();

            $detalles = $request->detalles;

            $punto = PuntoEmision::select('establecimiento_id', 'user_id')
                ->where('user_id', Auth::user()->id)
                ->first();

            foreach ($detalles as $key => $det) {
                $detalle = new DetalleFactura();
                $detalle->factura_id = $factura->id;
                $detalle->presentacion_id = $det['presentacion_id'];
                $detalle->det_cantidad = $det['cantidad'];
                $detalle->det_precio = $det['pre_venta'];
                $detalle->det_descuento = $det['des_individual'];
                $detalle->det_total = $det['sub_individual'];
                $detalle->save();

                $get = Inventario::select('id', 'presentacion_id', 'establecimiento_id')
                    ->where('presentacion_id', $det['presentacion_id'])
                    ->where('establecimiento_id', $punto->establecimiento_id)
                    ->first();

                $inventario = Inventario::findOrFail($get->id);
                $inventario->dis_stock = $inventario->dis_stock - $det['cantidad'];
                $inventario->save();

                $cliente = Cliente::select('id', 'nombre')
                    ->where('id', $factura->cliente_id)
                    ->first();

                $kardex = new Kardex();
                $kardex->inventario_id = $get->id;
                $kardex->concepto = mb_strtoupper('VENTA: FAC ' . $factura->num_secuencial . ' ' . $cliente->nombre);
                $kardex->cantidad = $det['cantidad'];
                $kardex->save();
            }

            if ($factura->for_pago == 'C') {
                $dt = new Carbon($factura->fec_emision);
                $credito = new Credito();
                $credito->id = $factura->id;
                $credito->saldo = $factura->val_total;
                $credito->abonos = 0;
                $credito->dias_credito = trim($request->dias_credito);
                $credito->fec_limite = $dt->addDays($request->dias_credito);
                $credito->observaciones = mb_strtoupper(trim($request->observaciones));
                $credito->estado = 'P';
                $credito->save();
            }

            DB::commit();
            $id = $factura->id;
            return
                [
                    'factura' => Factura::join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                        ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                        ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
                        ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                        ->join('tip_identificaciones', 'clientes.tip_identificacion_id', 'tip_identificaciones.id')
                        ->join('for_pagos', 'facturas.for_pago_id', 'for_pagos.id')
                        ->select(
                            'facturas.id',
                            'facturas.fec_emision',
                            'facturas.num_secuencial',
                            'facturas.tip_emision',
                            'facturas.tip_ambiente',
                            'facturas.cla_acceso',
                            'facturas.sub_sin_imp',
                            'facturas.sub_iva',
                            'facturas.sub_0',
                            'facturas.sub_no_iva',
                            'facturas.sub_exento',
                            'facturas.tot_descuento',
                            'facturas.val_ice',
                            'facturas.val_irbpnr',
                            'facturas.val_iva',
                            'facturas.val_propina',
                            'facturas.val_total',
                            'facturas.for_pago',
                            'puntos_emision.pun_codigo',
                            'establecimientos.est_codigo',
                            'establecimientos.nom_comercial',
                            'establecimientos.direccion as dir_establecimiento',
                            'empresas.raz_social',
                            'empresas.ruc',
                            'empresas.direccion as dir_matriz',
                            'empresas.obli_contabilidad',
                            'empresas.tip_regimen',
                            'empresas.age_retencion',
                            'empresas.firma',
                            'empresas.fir_clave',
                            'clientes.nombre',
                            'clientes.num_identificacion',
                            'clientes.direccion as dir_cliente',
                            'clientes.telefonos',
                            'clientes.email',
                            'tip_identificaciones.codigo as tip_cliente',
                            'for_pagos.codigo as forma'
                        )
                        ->where('facturas.id', $id)
                        ->first(),
                    'detalles' => DetalleFactura::join('presentaciones', 'detalles_factura.presentacion_id', 'presentaciones.id')
                        ->join('productos', 'presentaciones.producto_id', 'productos.id')
                        ->join('tar_agregados', 'productos.tar_agregado_id', 'tar_agregados.id')
                        ->join('imp_agregados', 'tar_agregados.imp_agregado_id', 'imp_agregados.id')
                        ->select(
                            'detalles_factura.factura_id',
                            'presentaciones.cod_principal',
                            'productos.nombre',
                            'presentaciones.pre_venta',
                            'detalles_factura.det_cantidad',
                            'detalles_factura.det_descuento',
                            'detalles_factura.det_total',
                            'imp_agregados.codigo',
                            'tar_agregados.codigo as cod_porcentaje',
                            'tar_agregados.valor'
                        )
                        ->where('detalles_factura.factura_id', $id)
                        ->get(),
                    'credito' => Credito::select('id', 'saldo', 'dias_credito')
                        ->where('id', $id)
                        ->first()
                ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function getInvoice(Request $request)
    {
        $sec_inicial = PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
            ->join('tip_ambientes', 'empresas.tip_ambiente_id', 'tip_ambientes.id')
            ->select(
                'puntos_emision.id',
                'puntos_emision.pun_codigo as punto',
                'puntos_emision.sec_factura',
                'puntos_emision.user_id',
                'establecimientos.est_codigo as establecimiento',
                'empresas.ruc',
                'tip_ambientes.codigo as tip_ambiente',
                'empresas.tip_emision',
                'empresas.firma',
                'empresas.fir_clave'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->first();
        $secuencial = Factura::join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
            ->select(
                'facturas.num_secuencial',
                'facturas.created_at'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $n_est = str_pad($sec_inicial->establecimiento, 3, "0", STR_PAD_LEFT);
        $n_pto = str_pad($sec_inicial->punto, 3, "0", STR_PAD_LEFT);
        if ($secuencial) {
            $consecutivo = $secuencial->num_secuencial + 1;
        } else {
            $consecutivo = $sec_inicial->sec_factura + 1;
        }
        if ($sec_inicial->tip_ambiente == 0) {
            $ambiente = '1';
        } else if ($sec_inicial->tip_ambiente == 1) {
            $ambiente = '2';
        }

        $fecha = date('dmY');
        $n_cons = str_pad($consecutivo, 9, "0", STR_PAD_LEFT);
        $hoy = date('d/m/Y');
        $num_comprobante = $n_est . '-' . $n_pto . '-' . $n_cons;
        $clave = $fecha . '01' . $sec_inicial->ruc . $ambiente . $n_est . $n_pto . $n_cons . '12345678' . '1';
        return [
            'punto_id' => $sec_inicial->id,
            'tip_ambiente' => $sec_inicial->tip_ambiente,
            'fec_emision' => $hoy,
            'num_secuencial' => $consecutivo,
            'cla_acceso' => $clave,
            'firma' => $sec_inicial->firma,
            'comprobante' => $num_comprobante,
            'fir_clave' => $sec_inicial->fir_clave
        ];
    }

    public function individualPdf(Request $request)
    {
        return response()->file('archivos/comprobantes/facturas/pdf/' . $request->clave . '.pdf');
    }

    public function individualXml(Request $request)
    {
        return response()->file('archivos/comprobantes/facturas/' . $request->clave . '.xml');
    }

    public function forwardingInvoice(Request $request)
    {
        $datos = Factura::join('clientes', 'facturas.cliente_id', 'clientes.id')
            ->select(
                'facturas.id',
                'facturas.cla_acceso',
                'clientes.nombre',
                'clientes.email'
            )
            ->where('facturas.id', $request->id)
            ->first();
        $empresa = Empresa::select(
            'corr_servidor',
            'corr_puerto',
            'corr_usuario',
            'corr_password'
        )
            ->first();
        $email = new sendEmail();
        $email->forwarding('Factura', $datos, $empresa);
    }
}
