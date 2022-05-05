<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstablecimientoController extends Controller
{
    public function index()
    {
        $establecimientos = Establecimiento::join('users', 'establecimientos.user_id', 'users.id')
            ->select(
                'users.usuario',
                'establecimientos.id',
                'establecimientos.est_codigo',
                'establecimientos.nom_comercial',
                'establecimientos.nom_referencia',
                'establecimientos.direccion',
                'establecimientos.telefonos',
                'establecimientos.estado'
            )
            ->orderBy('establecimientos.est_codigo', 'asc')
            ->get();
        return $establecimientos;
    }

    public function store(Request $request)
    {
        $establecimiento = new Establecimiento();
        $establecimiento->empresa_id = 1;
        $establecimiento->user_id = trim($request->user_id);
        $establecimiento->est_codigo = mb_strtoupper(trim($request->est_codigo));
        $establecimiento->nom_comercial = mb_strtoupper(trim($request->nom_comercial));
        $establecimiento->nom_referencia = mb_strtoupper(trim($request->nom_referencia));
        $establecimiento->direccion = mb_strtoupper(trim($request->direccion));
        $establecimiento->telefonos = mb_strtoupper(trim($request->telefonos));
        $establecimiento->save();
    }

    public function update(Request $request)
    {
        $establecimiento = Establecimiento::findOrFail($request->id);
        $establecimiento->user_id = trim($request->user_id);
        $establecimiento->est_codigo = mb_strtoupper(trim($request->est_codigo));
        $establecimiento->nom_comercial = mb_strtoupper(trim($request->nom_comercial));
        $establecimiento->nom_referencia = mb_strtoupper(trim($request->nom_referencia));
        $establecimiento->direccion = mb_strtoupper(trim($request->direccion));
        $establecimiento->telefonos = mb_strtoupper(trim($request->telefonos));
        $establecimiento->save();
    }

    public function detail(Request $request)
    {
        $establecimiento = Establecimiento::join('users', 'establecimientos.user_id', 'users.id')
            ->select(
                'establecimientos.id',
                'establecimientos.est_codigo',
                'establecimientos.nom_comercial',
                'establecimientos.nom_referencia',
                'establecimientos.direccion',
                'establecimientos.telefonos',
                'establecimientos.estado',
                'users.id as user_id'
            )
            ->where('establecimientos.id', $request->id)
            ->first();
        return $establecimiento;
    }

    public function activate(Request $request)
    {
        $establecimiento = Establecimiento::findOrFail($request->id);
        $establecimiento->estado = 1;
        $establecimiento->save();
    }

    public function deactivate(Request $request)
    {
        $establecimiento = Establecimiento::findOrFail($request->id);
        $establecimiento->estado = 0;
        $establecimiento->save();
    }

    public function onlyActive()
    {
        $establecimientos = Establecimiento::select(
            'id',
            'nom_comercial',
            'nom_referencia',
            'estado'
        )
            ->where('estado', 1)
            ->get();
        return $establecimientos;
    }

    public function responsibles()
    {
        $responsables = DB::select("SELECT u.id, u.usuario FROM users u WHERE NOT EXISTS(SELECT e.user_id FROM establecimientos e WHERE u.id = e.user_id)");
        return $responsables;
    }
}
