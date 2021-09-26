<?php

namespace App\Http\Controllers;

use App\Models\Unidad;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function index()
    {
        $unidades = Unidad::select(
            'id',
            'nombre',
            'sigla'
        )
            ->orderBy('nombre', 'asc')
            ->get();
        return $unidades;
    }

    public function store(Request $request)
    {
        $unidad = new Unidad();
        $unidad->nombre = mb_strtoupper(trim($request->nombre));
        $unidad->sigla = mb_strtoupper(trim($request->sigla));
        $unidad->save();
    }

    public function update(Request $request)
    {
        $unidad = Unidad::findOrFail($request->id);
        $unidad->nombre = mb_strtoupper(trim($request->nombre));
        $unidad->sigla = mb_strtoupper(trim($request->sigla));
        $unidad->save();
    }

    public function detail(Request $request)
    {
        $unidad = Unidad::select('id', 'nombre', 'sigla')
            ->where('id', $request->id)
            ->first();
        return $unidad;
    }

    public function validateName(Request $request)
    {
        $nombre = Unidad::select('nombre')
            ->where('nombre', $request->nombre)
            ->first();
        return $nombre;
    }
}
