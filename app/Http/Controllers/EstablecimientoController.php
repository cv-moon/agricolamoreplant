<?php

namespace App\Http\Controllers;

use App\Models\Establecimiento;
use Illuminate\Http\Request;

class EstablecimientoController extends Controller
{
    public function index()
    {
        $establecimientos = Establecimiento::select(
            'id',
            'numero',
            'nom_comercial',
            'nom_referencia',
            'direccion',
            'telefonos',
            'estado'
        )
            ->orderBy('numero', 'asc')
            ->get();
        return $establecimientos;
    }

    public function store(Request $request)
    {
        $establecimiento = new Establecimiento();
        $establecimiento->empresa_id = 1;
        $establecimiento->numero = mb_strtoupper(trim($request->numero));
        $establecimiento->nom_comercial = mb_strtoupper(trim($request->nom_comercial));
        $establecimiento->nom_referencia = mb_strtoupper(trim($request->nom_referencia));
        $establecimiento->direccion = mb_strtoupper(trim($request->direccion));
        $establecimiento->telefonos = mb_strtoupper(trim($request->telefonos));
        $establecimiento->save();
    }

    public function update(Request $request)
    {
        $establecimiento = Establecimiento::findOrFail($request->id);
        $establecimiento->nom_comercial = mb_strtoupper(trim($request->nom_comercial));
        $establecimiento->nom_referencia = mb_strtoupper(trim($request->nom_referencia));
        $establecimiento->direccion = mb_strtoupper(trim($request->direccion));
        $establecimiento->telefonos = mb_strtoupper(trim($request->telefonos));
        $establecimiento->save();
    }

    public function detail(Request $request)
    {
        $establecimiento = Establecimiento::select(
            'id',
            'numero',
            'nom_comercial',
            'nom_referencia',
            'direccion',
            'telefonos',
            'estado'
        )
            ->where('id', $request->id)
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
}
