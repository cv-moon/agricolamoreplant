<?php

namespace App\Http\Controllers;

use App\Models\AbonoCredito;
use App\Models\Credito;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->cliente == 0 && $request->estado == '0') {
            $creditos = Credito::join('facturas', 'creditos.id', '=', 'facturas.id')
                ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->select(
                    'creditos.id',
                    'creditos.saldo',
                    'creditos.abonos',
                    'creditos.dias_credito',
                    'creditos.fec_limite',
                    'creditos.observaciones',
                    'creditos.estado',
                    'clientes.nombre as cliente',
                    'facturas.num_secuencial',
                    'puntos_emision.codigo',
                    'establecimientos.numero',
                    'establecimientos.nom_referencia'
                )
                ->orderBy('creditos.fec_limite', 'desc')
                ->get();
            return  $creditos;
        } else if ($request->cliente != 0 && $request->estado == '0') {
            $creditos = Credito::join('facturas', 'creditos.id', '=', 'facturas.id')
                ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->select(
                    'creditos.id',
                    'creditos.saldo',
                    'creditos.abonos',
                    'creditos.dias_credito',
                    'creditos.fec_limite',
                    'creditos.observaciones',
                    'creditos.estado',
                    'clientes.id',
                    'clientes.nombre as cliente',
                    'facturas.num_secuencial',
                    'puntos_emision.codigo',
                    'establecimientos.numero',
                    'establecimientos.nom_referencia'
                )
                ->where('clientes.id', '=', $request->cliente)
                ->orderBy('creditos.fec_limite', 'desc')
                ->get();
            return  $creditos;
        } else if ($request->cliente == 0 && $request->estado != '0') {
            $creditos = Credito::join('facturas', 'creditos.id', '=', 'facturas.id')
                ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->select(
                    'creditos.id',
                    'creditos.saldo',
                    'creditos.abonos',
                    'creditos.dias_credito',
                    'creditos.fec_limite',
                    'creditos.observaciones',
                    'creditos.estado',
                    'clientes.id',
                    'clientes.nombre as cliente',
                    'facturas.num_secuencial',
                    'puntos_emision.codigo',
                    'establecimientos.numero',
                    'establecimientos.nom_referencia'
                )
                ->where('creditos.estado', '=', $request->estado)
                ->orderBy('creditos.fec_limite', 'desc')
                ->get();
            return  $creditos;
        } else if ($request->cliente != 0 && $request->estado != '0') {
            $creditos = Credito::join('facturas', 'creditos.id', '=', 'facturas.id')
                ->join('clientes', 'facturas.cliente_id', 'clientes.id')
                ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
                ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->select(
                    'creditos.id',
                    'creditos.saldo',
                    'creditos.abonos',
                    'creditos.dias_credito',
                    'creditos.fec_limite',
                    'creditos.observaciones',
                    'creditos.estado',
                    'clientes.id',
                    'clientes.nombre as cliente',
                    'facturas.num_secuencial',
                    'puntos_emision.codigo',
                    'establecimientos.numero',
                    'establecimientos.nom_referencia'
                )
                ->where('clientes.id', '=', $request->cliente)
                ->where('creditos.estado', '=', $request->estado)
                ->orderBy('creditos.fec_limite', 'desc')
                ->get();
            return  $creditos;
        }
    }

    public function detail(Request $request)
    {
        $credito = Credito::join('facturas', 'creditos.id', '=', 'facturas.id')
            ->join('clientes', 'facturas.cliente_id', '=', 'clientes.id')
            ->join('puntos_emision', 'facturas.punto_id', 'puntos_emision.id')
            ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->select(
                'creditos.id',
                'creditos.saldo',
                'creditos.abonos',
                'creditos.dias_credito',
                'creditos.fec_limite',
                'creditos.estado',
                'clientes.nombre as cliente',
                'facturas.num_secuencial',
                'facturas.created_at',
                'puntos_emision.codigo',
                'establecimientos.numero',
                'establecimientos.nom_referencia'
            )
            ->where('creditos.id', '=', $request->id)
            ->first();
        $abonos = AbonoCredito::select(
            'credito_id',
            'val_abono',
            'fec_abono',
            'observaciones'
        )
            ->where('credito_id', '=', $request->id)
            ->get();
        return ['credito' => $credito, 'abonos' => $abonos];
    }

    public function payment(Request $request)
    {
        $pago = new AbonoCredito();
        $pago->credito_id = trim($request->credito_id);
        $pago->val_abono = trim($request->val_abono);
        $pago->fec_abono = trim($request->fec_abono);
        $pago->observaciones = mb_strtoupper(trim($request->observaciones));
        $pago->save();
        $credito = Credito::findOrFail($request->credito_id);
        $credito->abonos = $credito->abonos + $pago->val_abono;
        if ($credito->saldo == $credito->abonos) {
            $credito->estado = 'C';
        }
        $credito->save();
    }
}
