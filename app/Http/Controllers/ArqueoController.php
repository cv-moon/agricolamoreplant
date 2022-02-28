<?php

namespace App\Http\Controllers;

use App\Models\Arqueo;
use App\Models\Egreso;
use App\Models\Factura;
use App\Models\PuntoEmision;
use Carbon\Carbon;
use generarPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

include 'class/generarPDF.php';

class ArqueoController extends Controller
{
    public function index()
    {
        $mes = date("m");
        if (Auth::user()->rol_id == 2) {
            $arqueos = Arqueo::join('puntos_emision', 'arqueos.punto_id', 'puntos_emision.id')
                ->join('users', 'puntos_emision.user_id', 'users.id')
                ->select(
                    'arqueos.id',
                    'arqueos.created_at',
                    'arqueos.mon_apertura',
                    'arqueos.fec_cierre',
                    'arqueos.mon_cierre',
                    'arqueos.tot_efectivo',
                    'arqueos.tot_credito',
                    'arqueos.tot_egreso',
                    'arqueos.estado',
                    'users.usuario as responsable'
                )
                ->where('users.id', Auth::user()->id)
                ->whereMonth('arqueos.created_at', $mes)
                ->orderBy('arqueos.created_at', 'desc')
                ->get();
        } else {
            $arqueos = Arqueo::join('puntos_emision', 'arqueos.punto_id', 'puntos_emision.id')
                ->join('users', 'puntos_emision.user_id', 'users.id')
                ->select(
                    'arqueos.id',
                    'arqueos.created_at',
                    'arqueos.mon_apertura',
                    'arqueos.fec_cierre',
                    'arqueos.mon_cierre',
                    'arqueos.tot_efectivo',
                    'arqueos.tot_credito',
                    'arqueos.tot_egreso',
                    'arqueos.estado',
                    'users.usuario as responsable'
                )
                ->whereMonth('arqueos.created_at', $mes)
                ->orderBy('arqueos.created_at', 'desc')
                ->get();
        }
        return $arqueos;
    }

    public function store(Request $request)
    {
        $arqueo = new Arqueo();
        $arqueo->punto_id = $request->punto_id;
        $arqueo->user_id = $request->user_id;
        $arqueo->mon_apertura = trim($request->mon_apertura);
        $arqueo->estado = 1;
        $arqueo->save();
    }

    public function update(Request $request)
    {
        $caja = Arqueo::findOrFail($request->id);
        $caja->fec_cierre = Carbon::now();
        $caja->mon_cierre = $request->mon_cierre;
        $caja->tot_efectivo = $request->tot_efectivo;
        $caja->tot_credito = $request->tot_credito;
        $caja->tot_egreso = $request->tot_egreso;
        $caja->observaciones = $request->observaciones;
        $caja->estado = 0;
        $caja->save();
    }

    public function detail(Request $request)
    {
        $arqueo = Arqueo::join('puntos_emision', 'arqueos.punto_id', 'puntos_emision.id')
            ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('users', 'puntos_emision.user_id', 'users.id')
            ->select(
                'arqueos.id',
                'arqueos.mon_apertura',
                'arqueos.mon_cierre',
                'arqueos.observaciones',
                'arqueos.estado',
                'puntos_emision.nombre as punto',
                'establecimientos.nom_referencia as establecimiento',
                'users.usuario'
            )
            ->where('arqueos.id', $request->id)
            ->first();
        $efectivo = Factura::where('for_pago', 'E')
            ->where('usuario_id', Auth::user()->id)
            ->whereDate('facturas.created_at', Carbon::now()->format('Y-m-d'))
            // ->where('estado', 'R')
            ->get()
            ->sum('val_total');
        $credito = Factura::where('for_pago', 'C')
            ->where('usuario_id', Auth::user()->id)
            ->whereDate('facturas.created_at', Carbon::now()->format('Y-m-d'))
            // ->where('estado', 'R')
            ->get()
            ->sum('val_total');
        $egreso = Egreso::where('egresos.arqueo_id', $request->id)
            ->whereDate('egresos.created_at', Carbon::now()->format('Y-m-d'))
            ->get()
            ->sum('valor');
        $gastos = Egreso::join('users', 'egresos.user_id', 'users.id')
            ->select(
                'egresos.descripcion',
                'egresos.valor',
                'users.usuario as beneficiario'
            )
            ->where('egresos.arqueo_id', $request->id)
            ->get();
        $suma = Egreso::join('arqueos', 'egresos.arqueo_id', 'arqueos.id')
            ->select(
                'egresos.arqueo_id',
                'egresos.descripcion',
                'egresos.valor'
            )
            ->where('egresos.arqueo_id', $request->id)
            ->get()
            ->sum('valor');
        return ['arqueo' => $arqueo, 'efectivo' => $efectivo, 'credito' => $credito, 'egreso' => $egreso, 'gastos' => $gastos, 'suma' => $suma];
    }

    public function validateToday()
    {
        $dia = date('Y-m-d');
        $arqueo = Arqueo::join('puntos_emision', 'arqueos.punto_id', 'puntos_emision.id')
            ->join('users', 'puntos_emision.user_id', 'users.id')
            ->select(
                'arqueos.created_at',
                'puntos_emision.id as punto_id',
                'puntos_emision.nombre as punto',
                'users.id as user_id',
                'users.usuario'
            )
            ->where('puntos_emision.user_id', Auth::user()->id)
            ->where('users.estado', 1)
            ->where('puntos_emision.estado', 1)
            ->where('arqueos.created_at', $dia)
            ->first();
        if ($arqueo) {
            return response()->json('open');
        } else {
            $datos = PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
                ->join('users', 'puntos_emision.user_id', 'users.id')
                ->select(
                    'puntos_emision.id as punto_id',
                    'puntos_emision.nombre as punto',
                    'establecimientos.nom_referencia as establecimiento',
                    'users.id as user_id',
                    'users.usuario'
                )
                ->where('puntos_emision.user_id', Auth::user()->id)
                ->where('users.estado', 1)
                ->where('puntos_emision.estado', 1)
                ->first();
            return $datos;
        }
    }

    public function getArqueo()
    {
        $arqueo = Arqueo::join('puntos_emision', 'arqueos.punto_id', 'puntos_emision.id')
            ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('users', 'puntos_emision.user_id', 'users.id')
            ->select(
                'arqueos.id',
                'arqueos.mon_apertura',
                'puntos_emision.nombre as punto',
                'establecimientos.nom_referencia as establecimiento',
                'users.usuario'
            )
            ->whereDate('arqueos.created_at', Carbon::now()->format('Y-m-d'))
            ->where('users.id', Auth::user()->id)
            ->where('users.estado', 0)
            ->where('puntos_emision.estado', 1)
            ->first();
        if (!$arqueo) {
            return response()->json('nothing');
        } else {
            $efectivo = Factura::where('for_pago', 'E')
                ->where('usuario_id', Auth::user()->id)
                ->whereDate('facturas.created_at', Carbon::now()->format('Y-m-d'))
                // ->where('estado', 'R')
                ->get()
                ->sum('val_total');
            $credito = Factura::where('for_pago', 'C')
                ->where('usuario_id', Auth::user()->id)
                ->whereDate('facturas.created_at', Carbon::now()->format('Y-m-d'))
                // ->where('estado', 'R')
                ->get()
                ->sum('val_total');
            $egreso = Egreso::where('egresos.arqueo_id', $arqueo->id)
                ->whereDate('egresos.created_at', Carbon::now()->format('Y-m-d'))
                ->get()
                ->sum('valor');
            $gastos = Egreso::join('users', 'egresos.user_id', 'users.id')
                ->select(
                    'egresos.descripcion',
                    'egresos.valor',
                    'users.usuario as beneficiario'
                )
                ->where('egresos.arqueo_id', $arqueo->id)
                ->get();
            $suma = Egreso::join('arqueos', 'egresos.arqueo_id', 'arqueos.id')
                ->select(
                    'egresos.arqueo_id',
                    'egresos.descripcion',
                    'egresos.valor'
                )
                ->where('egresos.arqueo_id', $arqueo->id)
                ->get()
                ->sum('valor');
            return ['arqueo' => $arqueo, 'efectivo' => $efectivo, 'credito' => $credito, 'egreso' => $egreso, 'gastos' => $gastos, 'suma' => $suma];
        }
    }

    public function printArqueo(Request $request)
    {
        $arqueo = Arqueo::join('puntos_emision', 'arqueos.punto_id', 'puntos_emision.id')
            ->join('users', 'puntos_emision.user_id', 'users.id')
            ->join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('empresas', 'establecimientos.empresa_id', 'empresas.id')
            ->select(
                'arqueos.mon_apertura',
                'arqueos.created_at',
                'arqueos.fec_cierre',
                'arqueos.mon_cierre',
                'arqueos.observaciones',
                'arqueos.estado',
                'empresas.logo',
                'establecimientos.nom_comercial as empresa',
                'establecimientos.nom_referencia as establecimiento',
                'establecimientos.direccion',
                'establecimientos.telefonos',
                'puntos_emision.nombre as punto',
                'users.id as usuario_id',
                'users.usuario as responsable'
            )
            ->where('arqueos.id', $request->id)
            ->first();
        $efectivo = Factura::select('num_secuencial', 'val_total', 'created_at')
            ->where('for_pago', 'E')
            ->where('usuario_id', $arqueo->usuario_id)
            ->whereDate('created_at', $arqueo->created_at)
            // ->where('estado', 'R')
            ->get();
        $credito = Factura::select('num_secuencial', 'val_total', 'created_at')
            ->where('for_pago', 'C')
            ->where('usuario_id', $arqueo->usuario_id)
            ->whereDate('created_at', $arqueo->created_at)
            // ->where('estado', 'R')
            ->get();
        $egresos = Egreso::join('users', 'egresos.user_id', 'users.id')
            ->select(
                'egresos.descripcion',
                'egresos.valor',
                'egresos.created_at',
                'users.usuario as beneficiario'
            )
            ->where('egresos.arqueo_id', $request->id)
            ->orderBy('egresos.created_at', 'asc')
            ->get();
        $Reporte = new generarPDF();
        $Reporte->cierreCaja($arqueo, $efectivo, $credito, $egresos);
    }
}
