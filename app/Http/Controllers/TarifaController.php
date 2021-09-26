<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function index()
    {
        $tarifas = Tarifa::join('impuestos', 'tarifas.impuesto_id', 'impuestos.id')
            ->select(
                'tarifas.id',
                'tarifas.nombre as tarifa',
                'tarifas.codigo',
                'tarifas.valor',
                'impuestos.nombre as impuesto'
            )
            ->orderBy('impuestos.nombre')
            ->get();
        return $tarifas;
    }

    public function store(Request $request)
    {
        $tarifa = new Tarifa();
        $tarifa->impuesto_id = $request->impuesto_id;
        $tarifa->nombre = mb_strtoupper(trim($request->nombre));
        $tarifa->codigo = trim($request->codigo);
        $tarifa->valor = trim($request->valor);
        $tarifa->save();
    }

    public function update(Request $request)
    {
        $tarifa = Tarifa::findOrFail($request->id);
        $tarifa->impuesto_id = $request->impuesto_id;
        $tarifa->nombre = mb_strtoupper(trim($request->nombre));
        $tarifa->codigo = trim($request->codigo);
        $tarifa->valor = trim($request->valor);
        $tarifa->save();
    }

    public function detail(Request $request)
    {
        $tarifa = Tarifa::select(
            'id',
            'impuesto_id',
            'nombre',
            'codigo',
            'valor'
        )
            ->where('id', $request->id)
            ->first();
        return $tarifa;
    }
}
