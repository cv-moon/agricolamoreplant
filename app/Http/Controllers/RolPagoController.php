<?php

namespace App\Http\Controllers;

use App\Models\DetalleRol;
use App\Models\Empleado;
use App\Models\Factura;
use App\Models\RolPago;
use Carbon\Carbon;
use generarPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
include 'class/generarPDF.php';

class RolPagoController extends Controller
{
    public function index()
    {
        $roles = RolPago::join('meses', 'roles_pago.mes_id', 'meses.id')
            ->join('users', 'roles_pago.user_id', 'users.id')
            ->join('empleados', 'users.id', 'empleados.id')
            ->select(
                'roles_pago.id',
                'roles_pago.ven_mensual',
                'roles_pago.por_venta',
                'roles_pago.tot_recibir',
                'roles_pago.estado',
                'roles_pago.created_at',
                'meses.nombre as mes',
                'empleados.nombres',
                'empleados.apellidos'
            )
            ->whereYear('roles_pago.created_at', date('Y'))
            ->get();

        return $roles;
    }

    public function getData(Request $request)
    {
        if ($request->mes != 0 && $request->usuario != 0) {
            $anterior = RolPago::select(
                'created_at'
            )
                ->whereMonth('created_at', $request->mes - 1)
                ->where('user_id', $request->usuario)
                ->first();
            $fecha = '';
            ($anterior) ? $fecha = $anterior->created_at : $fecha = date('Y-m-01');
            $ventas = Factura::select('val_total', 'created_at')
                ->whereBetween('created_at', [$fecha, Carbon::now()])
                ->where('usuario_id', $request->usuario)
                ->get()
                ->sum('val_total');
            $empleado = Empleado::select('salario')
                ->where('id', $request->usuario)
                ->first();
            return ['ventas' => $ventas, 'empleado' => $empleado];
        }
    }

    public function store(Request $request)
    {
        $rol = new RolPago();
        $rol->mes_id = $request->mes_id;
        $rol->user_id = $request->user_id;
        $rol->ven_mensual = $request->ven_mensual;
        $rol->por_venta = $request->por_venta;
        $rol->tot_recibir = $request->tot_recibir;
        $rol->estado = 'R';
        $rol->save();
    }

    public function update(Request $request)
    {
        $rol = RolPago::findOrFail($request->id);
        $rol->ven_mensual = $request->ven_mensual;
        $rol->por_venta = $request->por_venta;
        $rol->tot_recibir = $request->tot_recibir;
        $rol->estado = 'R';
        $rol->save();
    }
    public function detail(Request $request)
    {
        $rol = RolPago::join('users','roles_pago.user_id','users.id')
        ->join('empleados','users.id','empleados.id')
        ->select(
            'roles_pago.id',
            'roles_pago.mes_id',
            'roles_pago.user_id',
            'roles_pago.ven_mensual',
            'roles_pago.por_venta',
            'roles_pago.tot_recibir',
            'empleados.salario'
        )
            ->where('roles_pago.id', $request->id)
            ->first();
        return $rol;
    }

    public function payRol(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->id) {
                $rol = RolPago::findOrFail($request->id);
                $rol->ven_mensual = $request->ven_mensual;
                $rol->por_venta = $request->por_venta;
                $rol->tot_recibir = $request->tot_recibir;
                $rol->estado = 'G';
                $rol->save();
            } else {
                $rol = new RolPago();
                $rol->mes_id = $request->mes_id;
                $rol->user_id = $request->user_id;
                $rol->ven_mensual = $request->ven_mensual;
                $rol->por_venta = $request->por_venta;
                $rol->tot_recibir = $request->tot_recibir;
                $rol->estado = 'G';
                $rol->save();
            }

            $pago = new DetalleRol();
            $pago->id = $rol->id;
            $pago->for_pago = trim($request->for_pago);
            $pago->val_pago = trim($request->val_pago);
            $pago->observaciones = mb_strtoupper(trim($request->observaciones));
            $pago->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return $th;
        }
    }

    public function printRol(Request $request){
        $datos = RolPago::join('users','roles_pago.user_id','users.id')
        ->join('detalles_rol','roles_pago.id','detalles_rol.id')
        ->join('empleados','users.id','empleados.id')
        ->join('meses', 'roles_pago.mes_id','meses.id')
        ->join('puntos_emision','users.id','puntos_emision.user_id')
        ->join('establecimientos','puntos_emision.establecimiento_id','establecimientos.id')
        ->join('empresas','establecimientos.empresa_id','empresas.id')
        ->select(
            'roles_pago.id',
            'roles_pago.ven_mensual',
            'roles_pago.por_venta',
            'roles_pago.tot_recibir',
            'detalles_rol.for_pago',
            'detalles_rol.observaciones',
            'detalles_rol.created_at',
            'empleados.nombres',
            'empleados.apellidos',
            'empleados.salario',
            'empleados.num_identificacion',
            'meses.nombre as mes',
            'establecimientos.nom_referencia',
            'establecimientos.nom_comercial',
            'empresas.logo'
        )
        ->first();
        $Reporte = new generarPDF();
        $Reporte->rolPago($datos);
    }
}
