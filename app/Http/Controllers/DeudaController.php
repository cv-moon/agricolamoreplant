<?php

namespace App\Http\Controllers;

use App\Models\AbonoDeuda;
use App\Models\Deuda;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->proveedor == 0 && $request->estado == '0') {
            $deudas = Deuda::join('compras', 'deudas.id', '=', 'compras.id')
                ->join('proveedores', 'compras.proveedor_id', 'proveedores.id')
                ->select(
                    'deudas.id',
                    'deudas.saldo',
                    'deudas.abonos',
                    'deudas.dias_credito',
                    'deudas.fec_limite',
                    'deudas.observaciones',
                    'deudas.estado',
                    'proveedores.raz_social as proveedor'
                )
                ->orderBy('deudas.fec_limite', 'desc')
                ->get();
            return  $deudas;
        } else if ($request->proveedor != 0 && $request->estado == '0') {
            $deudas = Deuda::join('compras', 'deudas.id', '=', 'compras.id')
                ->join('proveedores', 'compras.proveedor_id', 'proveedores.id')
                ->select(
                    'deudas.id',
                    'deudas.saldo',
                    'deudas.abonos',
                    'deudas.dias_credito',
                    'deudas.fec_limite',
                    'deudas.observaciones',
                    'deudas.estado',
                    'proveedores.id',
                    'proveedores.raz_social as proveedor'
                )
                ->where('proveedores.id', '=', $request->proveedor)
                ->orderBy('deudas.fec_limite', 'desc')
                ->get();
            return  $deudas;
        } else if ($request->proveedor == 0 && $request->estado != '0') {
            $deudas = Deuda::join('compras', 'deudas.id', '=', 'compras.id')
                ->join('proveedores', 'compras.proveedor_id', 'proveedores.id')
                ->select(
                    'deudas.id',
                    'deudas.saldo',
                    'deudas.abonos',
                    'deudas.dias_credito',
                    'deudas.fec_limite',
                    'deudas.observaciones',
                    'deudas.estado',
                    'proveedores.id',
                    'proveedores.raz_social as proveedor'
                )
                ->where('deudas.estado', '=', $request->estado)
                ->orderBy('deudas.fec_limite', 'desc')
                ->get();
            return  $deudas;
        } else if ($request->proveedor != 0 && $request->estado != '0') {
            $deudas = Deuda::join('compras', 'deudas.id', '=', 'compras.id')
                ->join('proveedores', 'compras.proveedor_id', 'proveedores.id')
                ->select(
                    'deudas.id',
                    'deudas.saldo',
                    'deudas.abonos',
                    'deudas.dias_credito',
                    'deudas.fec_limite',
                    'deudas.observaciones',
                    'deudas.estado',
                    'proveedores.id',
                    'proveedores.raz_social as proveedor'
                )
                ->where('proveedores.id', '=', $request->proveedor)
                ->where('deudas.estado', '=', $request->estado)
                ->orderBy('deudas.fec_limite', 'desc')
                ->get();
            return  $deudas;
        }
    }

    public function detail(Request $request)
    {
        $deuda = Deuda::join('compras', 'deudas.id', '=', 'compras.id')
            ->join('proveedores', 'compras.proveedor_id', '=', 'proveedores.id')
            ->select(
                'deudas.id',
                'deudas.saldo',
                'deudas.abonos',
                'deudas.dias_credito',
                'deudas.fec_limite',
                'deudas.estado',
                'proveedores.raz_social as proveedor',
                'compras.tip_comprobante',
                'compras.num_comprobante',
                'compras.fec_emision'
            )
            ->where('deudas.id', '=', $request->id)
            ->first();
        $abonos = AbonoDeuda::select(
            'deuda_id',
            'val_abono',
            'fec_abono',
            'obs_abono'
        )
            ->where('deuda_id', '=', $request->id)
            ->get();
        return ['deuda' => $deuda, 'abonos' => $abonos];
    }

    public function payment(Request $request)
    {
        $pago = new AbonoDeuda();
        $pago->deuda_id = trim($request->deuda_id);
        $pago->val_abono = trim($request->val_abono);
        $pago->fec_abono = trim($request->fec_abono);
        $pago->obs_abono = mb_strtoupper(trim($request->obs_abono));
        $pago->save();
        $deuda = Deuda::findOrFail($request->deuda_id);
        $deuda->abonos = $deuda->abonos + $pago->val_abono;
        if ($deuda->saldo == $deuda->abonos) {
            $deuda->estado = 'C';
        }
        $deuda->save();
    }
}
