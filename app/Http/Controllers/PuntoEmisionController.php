<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use App\Models\PuntoEmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuntoEmisionController extends Controller
{
    public function index()
    {
        $puntos = PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->join('users', 'puntos_emision.user_id', 'users.id')
            ->select(
                'puntos_emision.id',
                'puntos_emision.pun_codigo',
                'puntos_emision.nombre',
                'puntos_emision.sec_factura',
                'puntos_emision.sec_retencion',
                'puntos_emision.sec_gui_remision',
                'puntos_emision.sec_orden_trabajo',
                'puntos_emision.sec_recibo_cobro',
                'puntos_emision.estado',
                'establecimientos.nom_referencia',
                'users.usuario'
            )
            ->orderBy('establecimientos.nom_referencia', 'asc')
            ->where('establecimientos.estado', 1)
            ->get();
        return $puntos;
    }

    public function store(Request $request)
    {
        $punto = new PuntoEmision();
        $punto->establecimiento_id = trim($request->establecimiento_id);
        $punto->user_id = trim($request->user_id);
        $punto->pun_codigo = trim($request->pun_codigo);
        $punto->nombre = mb_strtoupper(trim($request->nombre));
        $punto->sec_factura = trim($request->sec_factura);
        $punto->sec_retencion = trim($request->sec_retencion);
        $punto->sec_gui_remision = trim($request->sec_gui_remision);
        $punto->sec_orden_trabajo = trim($request->sec_orden_trabajo);
        $punto->sec_recibo_cobro = trim($request->sec_recibo_cobro);
        $punto->save();
    }

    public function update(Request $request)
    {
        $punto = PuntoEmision::findOrFail($request->id);
        $punto->establecimiento_id = trim($request->establecimiento_id);
        $punto->user_id = trim($request->user_id);
        $punto->pun_codigo = trim($request->pun_codigo);
        $punto->nombre = mb_strtoupper(trim($request->nombre));
        $punto->sec_factura = trim($request->sec_factura);
        $punto->sec_retencion = trim($request->sec_retencion);
        $punto->sec_gui_remision = trim($request->sec_gui_remision);
        $punto->sec_orden_trabajo = trim($request->sec_orden_trabajo);
        $punto->sec_recibo_cobro = trim($request->sec_recibo_cobro);
        $punto->save();
    }

    public function detail(Request $request)
    {
        $punto =  PuntoEmision::join('establecimientos', 'puntos_emision.establecimiento_id', 'establecimientos.id')
            ->select(
                'puntos_emision.id',
                'puntos_emision.establecimiento_id',
                'puntos_emision.user_id',
                'puntos_emision.pun_codigo',
                'puntos_emision.nombre',
                'puntos_emision.sec_factura',
                'puntos_emision.sec_retencion',
                'puntos_emision.sec_gui_remision',
                'puntos_emision.sec_orden_trabajo',
                'puntos_emision.sec_recibo_cobro',
                'establecimientos.est_codigo'
            )
            ->where('puntos_emision.id', $request->id)
            ->first();

        return $punto;
    }

    public function activate(Request $request)
    {
        $punto = PuntoEmision::findOrFail($request->id);
        $punto->estado = 1;
        $punto->save();
    }

    public function deactivate(Request $request)
    {
        $punto = PuntoEmision::findOrFail($request->id);
        $punto->estado = 0;
        $punto->save();
    }

    public function validateName(Request $request)
    {
        $nombre = PuntoEmision::select('pun_codigo', 'establecimiento_id', 'nombre')
            ->where('nombre', mb_strtoupper(trim($request->nombre)))
            ->where('establecimiento_id', $request->est_id)
            ->where('pun_codigo', $request->pun_codigo)
            ->first();
        return $nombre;
    }

    public function validateCode(Request $request)
    {
        $codigo = PuntoEmision::select('establecimiento_id', 'pun_codigo')
            ->where('establecimiento_id', $request->est_id)
            ->where('pun_codigo', $request->pun_codigo)
            ->first();
        return $codigo;
    }

    public function responsibles()
    {
        $responsables = DB::select("SELECT u.id, u.usuario FROM users u WHERE NOT EXISTS(SELECT pe.user_id FROM puntos_emision pe WHERE u.id = pe.user_id)");
        return $responsables;
    }

    public function establishments()
    {
        $establecimientos = Establecimiento::select(
            'id',
            'est_codigo',
            'nom_comercial',
            'nom_referencia',
            'estado'
        )
            ->where('estado', 1)
            ->get();
        return $establecimientos;
    }
}
