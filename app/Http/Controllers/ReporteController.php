<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Empresa;
use App\Models\Factura;
use App\Models\PuntoEmision;
use generarPDF;
use Illuminate\Http\Request;

include 'class/generarPDF.php';

class ReporteController extends Controller
{
    public function reportSales(Request $request)
    {
        $hoy = date('Y-m-d');
        $empresa = Empresa::select('logo')->first();
        $data = Factura::join('clientes', 'facturas.cliente_id', 'clientes.id')
            ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
            ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->select(
                'facturas.num_secuencial',
                'facturas.created_at',
                'facturas.val_total',
                'facturas.for_pago',
                'clientes.nombre as cliente',
                'establecimientos.nom_comercial',
                'establecimientos.nom_referencia',
                'establecimientos.numero',
                'puntos_emision.codigo'
            )
            ->whereBetween('facturas.created_at', [$request->desde, $request->hasta])
            ->orWhere('clientes.id', $request->cliente)
            ->orWhere('establecimientos.id', $request->establecimiento)
            ->orderBy('facturas.created_at', 'asc')
            ->get();

        $reporte = new generarPDF();
        $reporte->reporteVentas($empresa, $data, $request->desde, $request->hasta);
    }

    public function reportPurchases(Request $request)
    {
        $hoy = date('Y-m-d');
        $empresa = Empresa::select('logo')->first();
        $data = Compra::join('proveedores', 'compras.proveedor_id', 'proveedores.id')
            ->join('establecimientos', 'compras.establecimiento_id', 'establecimientos.id')
            ->select(
                'compras.tip_comprobante',
                'compras.num_comprobante',
                'compras.created_at',
                'compras.for_pago',
                'compras.total',
                'compras.estado',
                'proveedores.nombre as proveedor',
                'establecimientos.nom_comercial',
                'establecimientos.nom_referencia'
            )
            ->whereBetween('compras.created_at', [$request->desde, $request->hasta])
            ->orWhere('proveedores.id', $request->proveedor)
            ->orwhere('compras.estado', $request->estado)
            ->get();
        $reporte = new generarPDF();
        $reporte->reporteCompras($empresa, $data, $request->desde, $request->hasta);
    }
}
