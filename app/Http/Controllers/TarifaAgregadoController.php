<?php

namespace App\Http\Controllers;

use App\Models\TarifaAgregado;
use Illuminate\Http\Request;

class TarifaAgregadoController extends Controller
{
    public function index()
    {
        $tar_agregados = TarifaAgregado::join('imp_agregados', 'tar_agregados.imp_agregado_id', 'imp_agregados.id')
            ->select(
                'tar_agregados.id',
                'tar_agregados.nombre as tarifa',
                'tar_agregados.codigo',
                'tar_agregados.valor',
                'imp_agregados.nombre as impuesto'
            )
            ->orderBy('imp_agregados.nombre')
            ->get();
        return $tar_agregados;
    }

    public function store(Request $request)
    {
        $tarifa = new TarifaAgregado();
        $tarifa->impuesto_id = $request->impuesto_id;
        $tarifa->nombre = mb_strtoupper(trim($request->nombre));
        $tarifa->codigo = trim($request->codigo);
        $tarifa->valor = trim($request->valor);
        $tarifa->save();
    }

    public function update(Request $request)
    {
        $tarifa = TarifaAgregado::findOrFail($request->id);
        $tarifa->impuesto_id = $request->impuesto_id;
        $tarifa->nombre = mb_strtoupper(trim($request->nombre));
        $tarifa->codigo = trim($request->codigo);
        $tarifa->valor = trim($request->valor);
        $tarifa->save();
    }

    public function detail(Request $request)
    {
        $tarifa = TarifaAgregado::select(
            'id',
            'imp_agregado_id',
            'nombre',
            'codigo',
            'valor'
        )
            ->where('id', $request->id)
            ->first();
        return $tarifa;
    }
}
