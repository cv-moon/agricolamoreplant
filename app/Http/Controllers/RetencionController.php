<?php

namespace App\Http\Controllers;

use App\Models\DetalleRetencion;
use App\Models\Empresa;
use App\Models\PuntoEmision;
use App\Models\Retencion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use sendEmail;

class RetencionController extends Controller
{
    public function index()
    {
        $mes = date("m");
        if (Auth::user()->rol_id == 1) {
            $retenciones = Retencion::join('proveedores', 'retenciones.proveedor_id', 'proveedores.id')
                ->join('puntos_emision', 'retenciones.punto_id', 'puntos_emision.id')
                ->join('establecimientos','puntos_emision.establecimiento_id','establecimientos.id')
               ->join('users', 'retenciones.usuario_id', 'users.id')
                ->select(
                    'retenciones.id',
                    'retenciones.fec_emision',
                    'retenciones.num_secuencial',
                    'retenciones.cla_acceso',
                    'retenciones.respuesta',
                    'proveedores.nombre',
                    'establecimientos.numero as establecimiento',
                    'puntos_emision.codigo as punto',
                    'users.usuario'
                )
                ->whereMonth('retenciones.fec_emision', $mes)
                ->orderBy('retenciones.created_at', 'desc')
                ->get();
        }
        if (Auth::user()->rol_id == 2) {
            $retenciones = Retencion::join('proveedores', 'retenciones.proveedor_id', 'proveedores.id')
            ->join('puntos_emision', 'retenciones.punto_id', 'puntos_emision.id')
            ->join('establecimientos','puntos_emision.establecimiento_id','establecimientos.id')
           ->join('users', 'retenciones.usuario_id', 'users.id')
            ->select(
                'retenciones.id',
                'retenciones.fec_emision',
                'retenciones.num_secuencial',
                'retenciones.cla_acceso',
                'retenciones.respuesta',
                'proveedores.nombre',
                'establecimientos.numero as establecimiento',
                'puntos_emision.codigo as punto',
                'users.usuario'
            )
                ->where('retenciones.usuario_id', Auth::user()->id)
                ->whereMonth('retenciones.fec_emision', $mes)
                ->orderBy('retenciones.num_secuencial', 'desc')
                ->get();
        }
        return $retenciones;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $rentencion = new Retencion();
            $rentencion->punto_id = $request->punto_id;
            $rentencion->usuario_id = Auth::user()->id;
            $rentencion->proveedor_id = $request->proveedor_id;
            $rentencion->fec_emision = date('Y-m-d');
            $rentencion->fec_autorizacion = date('Y-m-d');
            $rentencion->num_secuencial = $request->num_secuencial;
            $rentencion->tip_emision = $request->tip_emision;
            $rentencion->tip_ambiente = $request->tip_ambiente;
            $rentencion->cla_acceso = $request->cla_acceso;
            $rentencion->num_autorizacion = $request->cla_acceso;
            $rentencion->estado = 'R';
            $rentencion->save();

            $detalles = $request->detalles;

            foreach ($detalles as $key => $det) {
                $detalle = new DetalleRetencion();
                $detalle->retencion_id = $rentencion->id;
                $detalle->compra_id = $det['compra_id'];
                $detalle->comprobante_id = $det['comprobante_id'];
                $detalle->tarifas_retencion_id = $det['tarifas_retencion_id'];
                $detalle->num_comprobante = $det['num_comprobante'];
                $detalle->fec_emi_comprobante = $det['fec_emi_comprobante'];
                $detalle->eje_fiscal = $det['eje_fiscal'];
                $detalle->bas_imponible = $det['bas_imponible'];
                $detalle->val_Retenido = $det['val_Retenido'];
                $detalle->save();
            }

            DB::commit();
            $id = $rentencion->id;
            return
                [
                    'retencion' => Retencion::join('facturas', 'retenciones.factura_id', 'facturas.id')
                        ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                        ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                        ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
                        ->join('transportistas', 'retenciones.transportista_id', 'transportistas.id')
                        ->join('identificaciones', 'transportistas.identificacion_id', 'identificaciones.id')
                        ->select(
                            'retenciones.id',
                            'retenciones.fec_emision',
                            'retenciones.num_secuencial',
                            'retenciones.tip_emision',
                            'retenciones.tip_ambiente',
                            'retenciones.cla_acceso',
                            'retenciones.fec_inicio',
                            'retenciones.fec_fin',
                            'retenciones.des_nombre',
                            'retenciones.des_identificacion',
                            'retenciones.des_direccion',
                            'retenciones.motivo',
                            'retenciones.ruta',
                            'facturas.cla_acceso as factura',
                            'puntos_emision.codigo',
                            'establecimientos.numero',
                            'establecimientos.nom_comercial',
                            'establecimientos.direccion as dir_establecimiento',
                            'empresas.raz_social',
                            'empresas.ruc',
                            'empresas.direccion as dir_matriz',
                            'empresas.obli_contabilidad',
                            'empresas.reg_microempresa',
                            'empresas.age_retencion',
                            'empresas.firma',
                            'empresas.fir_clave',
                            'transportistas.nombre',
                            'transportistas.num_identificacion',
                            'transportistas.placa',
                            'identificaciones.codigo as tip_transportista'
                        )
                        ->where('retenciones.id', $id)
                        ->first(),
                    'detalles' => Retencion::join('productos', 'detalles_factura.producto_id', 'productos.id')
                        ->join('tarifas', 'productos.tarifa_id', 'tarifas.id')
                        ->select(
                            'detalles_factura.factura_id',
                            'productos.cod_principal',
                            'productos.nombre',
                            'productos.pre_venta',
                            'detalles_factura.det_cantidad',
                            'detalles_factura.det_descuento',
                            'detalles_factura.det_total',
                            'tarifas.valor',
                            'tarifas.codigo'
                        )
                        ->where('detalles_factura.factura_id', $id)
                        ->get()
                ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    public function getRetention()
    {
        $sec_inicial = PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
            ->select(
                'puntos_emision.id',
                'puntos_emision.codigo as punto',
                'puntos_emision.sec_retencion',
                'puntos_emision.user_id',
                'establecimientos.numero as establecimiento',
                'empresas.ruc',
                'empresas.tip_ambiente',
                'empresas.tip_emision',
                'empresas.firma',
                'empresas.fir_clave'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->first();
        $secuencial = Retencion::join('puntos_emision', 'retenciones.punto_id', 'puntos_emision.id')
            ->select(
                'retenciones.num_secuencial',
                'retenciones.created_at'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->orderBy('retenciones.created_at', 'desc')
            ->first();
        $n_est = str_pad($sec_inicial->establecimiento, 3, "0", STR_PAD_LEFT);
        $n_pto = str_pad($sec_inicial->punto, 3, "0", STR_PAD_LEFT);
        if ($secuencial) {
            $consecutivo = $secuencial->num_secuencial + 1;
        } else {
            $consecutivo = $sec_inicial->sec_retencion + 1;
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
        $clave = $fecha . '07' . $sec_inicial->ruc . $ambiente . $n_est . $n_pto . $n_cons . '12345678' . '1';
        return [
            'punto_id' => $sec_inicial->id,
            'tip_ambiente' => $sec_inicial->tip_ambiente,
            'tip_emision' => $sec_inicial->tip_emision,
            'fec_emision' => $hoy,
            'num_secuencial' => $consecutivo,
            'cla_acceso' => $clave,
            'firma' => $sec_inicial->firma,
            'comprobante' => $num_comprobante,
            'fir_clave' => $sec_inicial->fir_clave
        ];
    }

    public function getInvoices()
    {
        $comprobantes = DB::select("SELECT f.id, f.num_secuencial, e.nom_referencia FROM facturas f inner JOIN puntos_emision pe on f.punto_id = pe.id inner join establecimientos e on pe.establecimiento_id = e.id WHERE NOT EXISTS(SELECT g.factura_id FROM guias g WHERE f.id = g.factura_id)");
        return $comprobantes;
    }

    public function getDetails(Request $request)
    {
        $destinatario = Retencion::join('clientes', 'facturas.cliente_id', 'clientes.id')
            ->select(
                'clientes.nombre',
                'clientes.num_identificacion',
                'clientes.direccion'
            )
            ->where('facturas.id', $request->factura)
            ->first();
        $detalles = DetalleRetencion::join('productos', 'detalles_factura.producto_id', 'productos.id')
            ->select(
                'productos.id',
                'productos.nombre',
                'detalles_factura.det_cantidad'
            )
            ->where('detalles_factura.factura_id', $request->factura)
            ->get();
        return ['detalles' => $detalles, 'destinatario' => $destinatario];
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
        $datos = Retencion::join('clientes', 'facturas.cliente_id', 'clientes.id')
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
