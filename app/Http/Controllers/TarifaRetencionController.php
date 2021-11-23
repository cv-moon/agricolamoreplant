<?php

namespace App\Http\Controllers;

use App\Models\TarifaRetencion;
use Illuminate\Http\Request;

class TarifaRetencionController extends Controller
{
    public function index()
    {
        $tarifas = TarifaRetencion::join('impuestos_retencion', 'tarifas_retencion.impuesto_id', 'impuestos_retencion.id')
            ->select(
                'tarifas_retencion.id',
                'tarifas_retencion.nombre as tarifa',
                'tarifas_retencion.codigo',
                'tarifas_retencion.valor',
                'impuestos_retencion.nombre as impuesto'
            )
            ->orderBy('impuestos_retencion.nombre')
            ->get();
        return $tarifas;
    }

    public function store(Request $request)
    {
        $tarifa = new TarifaRetencion();
        $tarifa->impuesto_id = $request->impuesto_id;
        $tarifa->nombre = mb_strtoupper(trim($request->nombre));
        $tarifa->codigo = trim($request->codigo);
        $tarifa->valor = trim($request->valor);
        $tarifa->save();
    }

    public function update(Request $request)
    {
        $tarifa = TarifaRetencion::findOrFail($request->id);
        $tarifa->impuesto_id = $request->impuesto_id;
        $tarifa->nombre = mb_strtoupper(trim($request->nombre));
        $tarifa->codigo = trim($request->codigo);
        $tarifa->valor = trim($request->valor);
        $tarifa->save();
    }

    public function detail(Request $request)
    {
        $tarifa = TarifaRetencion::select(
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
