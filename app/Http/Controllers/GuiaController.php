<?php

namespace App\Http\Controllers;

use App\Models\DetalleGuia;
use App\Models\Empresa;
use App\Models\Guia;
use App\Models\PuntoEmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use sendEmail;

class GuiaController extends Controller
{
    public function index()
    {
        $mes = date("m");
        if (Auth::user()->rol_id == 1) {
            $guias = Guia::join('facturas', 'guias.factura_id', 'facturas.id')
                ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->join('users', 'facturas.usuario_id', 'users.id')
                ->select(
                    'guias.id',
                    'guias.fec_emision',
                    'guias.num_secuencial',
                    'guias.cla_acceso',
                    'guias.respuesta',
                    'facturas.cla_acceso as factura',
                    'clientes.nombre as cliente',
                    'clientes.num_identificacion',
                    'establecimientos.numero as establecimiento',
                    'puntos_emision.codigo as punto',
                    'users.usuario'
                )
                ->whereMonth('guias.fec_emision', $mes)
                ->orderBy('guias.created_at', 'desc')
                ->get();
        }
        if (Auth::user()->rol_id == 2) {
            $guias = Guia::join('facturas', 'guias.factura_id', 'facturas.id')
                ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->join('users', 'facturas.usuario_id', 'users.id')
                ->select(
                    'guias.id',
                    'guias.fec_emision',
                    'guias.num_secuencial',
                    'guias.cla_acceso',
                    'guias.respuesta',
                    'facturas.cla_acceso as factura',
                    'clientes.nombre as cliente',
                    'clientes.num_identificacion',
                    'establecimientos.numero as establecimiento',
                    'puntos_emision.codigo as punto',
                    'users.usuario'
                )
                ->where('guias.usuario_id', Auth::user()->id)
                ->whereMonth('guias.fec_emision', $mes)
                ->orderBy('guias.num_secuencial', 'desc')
                ->get();
        }
        return $guias;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $guia = new Guia();
            $guia->factura_id = $request->factura_id;
            $guia->usuario_id = Auth::user()->id;
            $guia->transportista_id = $request->transportista_id;
            $guia->punto_id = $request->punto_id;
            $guia->tip_ambiente = $request->tip_ambiente;
            $guia->tip_emision = $request->tip_emision;
            $guia->fec_emision = date('Y-m-d');
            $guia->fec_autorizacion = date('Y-m-d');
            $guia->num_secuencial = $request->num_secuencial;
            $guia->cla_acceso = $request->cla_acceso;
            $guia->num_autorizacion = $request->cla_acceso;
            $guia->fec_inicio = $request->fec_inicio;
            $guia->fec_fin = $request->fec_fin;
            $guia->motivo = $request->motivo;
            $guia->ruta = $request->ruta;
            $guia->observaciones = $request->observaciones;
            $guia->estado = 'R';
            $guia->save();

            $detalles = $request->detalles;

            $punto = PuntoEmision::select('establecimiento_id', 'user_id')
                ->where('user_id', Auth::user()->id)
                ->first();

            foreach ($detalles as $key => $det) {
                $detalle = new DetalleGuia();
                $detalle->factura_id = $guia->id;
                $detalle->producto_id = $det['producto_id'];
                $detalle->det_cantidad = $det['cantidad'];
                $detalle->save();
            }

            DB::commit();
            $id = $guia->id;
            return
                [
                    'guia' => Guia::join('facturas','guias.factura_id','facturas.id')
                    ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                        ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                        ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
                        ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                        ->join('identificaciones', 'clientes.identificacion_id', 'identificaciones.id')
                        ->select(
                            'guias.id',
                            'guias.fec_emision',
                            'guias.num_secuencial',
                            'guias.tip_emision',
                            'guias.tip_ambiente',
                            'guias.cla_acceso',
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
                            'clientes.nombre',
                            'clientes.num_identificacion',
                            'clientes.direccion as dir_cliente',
                            'clientes.telefonos',
                            'clientes.email',
                            'identificaciones.codigo as tip_cliente',
                            'formas_pago.codigo as forma'
                        )
                        ->where('guias.id', $id)
                        ->first(),
                    'detalles' => DetalleGuia::join('productos', 'detalles_factura.producto_id', 'productos.id')
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
        }
    }

    public function getGuide(Request $request)
    {
        $sec_inicial = PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
            ->select(
                'puntos_emision.id',
                'puntos_emision.codigo as punto',
                'puntos_emision.sec_gui_remision',
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
        $secuencial = Guia::join('puntos_emision', 'guias.punto_id', 'puntos_emision.id')
            ->select(
                'guias.num_secuencial',
                'guias.created_at'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->orderBy('guias.created_at', 'desc')
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
        $datos = Guia::join('clientes', 'facturas.cliente_id', 'clientes.id')
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
