<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio = date('Y');
        $mes = date('m');
        $hoy = date('d');
        $ventas = DB::table('facturas')
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('SUM(val_total) as total')
            )
            ->whereYear('created_at', $anio)
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
            ->get();
        $compras = DB::table('compras')
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('YEAR(created_at) as anio'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('created_at', $anio)
            ->where('estado', 1)
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'))
            ->get();
        $vh_efectivo = DB::table('facturas')
            ->select(DB::raw('SUM(val_total) as venta_diaria'))
            ->whereDay('created_at', $hoy)
            ->where('for_pago', 'E')
            ->first();
        $vh_credito = DB::table('facturas')
            ->select(DB::raw('SUM(val_total) as venta_diaria'))
            ->whereDay('created_at', $hoy)
            ->where('for_pago', 'C')
            ->first();
        $cpp = DB::table('deudas')
            ->select(DB::raw('COUNT(id) as cpp'))
            ->whereDay('fec_limite', $hoy)
            ->first();
        // $cpp = DB::select("SELECT COUNT(id) as cpp from deudas WHERE fec_limite = '" . date('Y-m-d') . "'");
        $p_agotado = DB::select('SELECT COUNT(dis_stock) as agotados from inventarios WHERE dis_stock <=  min_stock');
        //->where('dis_stock', '<=', 'min_stock')
        // ->first();
        return ['ventas' => $ventas, 'compras' => $compras, 'vh_efectivo' => $vh_efectivo, 'vh_credito' => $vh_credito, 'sold_out' => $p_agotado, 'cpp' => $cpp];
    }
}
