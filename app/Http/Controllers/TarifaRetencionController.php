<?php

namespace App\Http\Controllers;

use App\Models\TarifaRetencion;
use Illuminate\Http\Request;

class TarifaRetencionController extends Controller
{
    public function index()
    {
        $tar_retenciones = TarifaRetencion::join('imp_retenciones', 'tar_retenciones.imp_retencion_id', 'imp_retenciones.id')
            ->select(
                'tar_retenciones.id',
                'tar_retenciones.nombre as tarifa',
                'tar_retenciones.codigo',
                'tar_retenciones.valor',
                'imp_retenciones.nombre as impuesto'
            )
            ->orderBy('imp_retenciones.nombre')
            ->get();
        return $tar_retenciones;
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

    public function taxes()
    {
        $tarifas = TarifaRetencion::select('id', 'impuesto_id', 'nombre', 'codigo', 'valor')
            ->get();
        return $tarifas;
    }
}
