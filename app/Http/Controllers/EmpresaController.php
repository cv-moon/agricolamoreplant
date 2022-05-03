<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function detail()
    {
        $detalle = Empresa::first();
        return $detalle;
    }

    public function store(Request $request)
    {
        $empresa = new Empresa();
        $empresa->tip_ambiente_id = mb_strtoupper(trim($request->tip_ambiente_id));
        $empresa->raz_social = mb_strtoupper(trim($request->raz_social));
        $empresa->ruc = trim($request->ruc);
        $empresa->direccion = mb_strtoupper(trim($request->direccion));
        $empresa->cont_resolucion = trim($request->cont_resolucion);
        $empresa->obli_contabilidad = trim($request->obli_contabilidad);
        $empresa->tip_regimen = trim($request->tip_regimen);
        $empresa->age_retencion = mb_strtoupper(trim($request->age_retencion));
        $empresa->tip_emision = trim($request->tip_emision);
        $empresa->fir_clave = trim($request->fir_clave);
        if ($request->fir_vencimiento) {
            $empresa->fir_vencimiento = trim($request->fir_vencimiento);
        }

        $carpetaprincipal = 'archivos';
        if ($request->hasFile('logo')) {
            $nombre_imagen = $request->file('logo')->getClientOriginalName();
            $empresa->logo = 'storage/' . $carpetaprincipal . '/logo/' . $nombre_imagen;
            $request->file('logo')->storeAs($carpetaprincipal . '/logo/', $nombre_imagen, 'public');
        }
        if ($request->hasFile('firma')) {
            $nombre_firma = $request->file('firma')->getClientOriginalName();
            $empresa->firma = 'storage/' . $carpetaprincipal . '/firma/' . $nombre_firma;
            $request->file('firma')->storeAs($carpetaprincipal . '/firma/', $nombre_firma, 'public');
        }

        if (!file_exists('archivos/comprobantes/facturas')) {
            mkdir('archivos/comprobantes/facturas', 0777, true);
        }


        $empresa->save();
    }

    public function update(Request $request)
    {
        $empresa = Empresa::findOrFail($request->id);
        $empresa->tip_ambiente_id = mb_strtoupper(trim($request->tip_ambiente_id));
        $empresa->raz_social = mb_strtoupper(trim($request->raz_social));
        $empresa->ruc = trim($request->ruc);
        $empresa->direccion = mb_strtoupper(trim($request->direccion));
        $empresa->cont_resolucion = trim($request->cont_resolucion);
        $empresa->obli_contabilidad = trim($request->obli_contabilidad);
        $empresa->tip_regimen = trim($request->tip_regimen);
        $empresa->age_retencion = mb_strtoupper(trim($request->age_retencion));
        $empresa->tip_emision = trim($request->tip_emision);
        $empresa->fir_clave = trim($request->fir_clave);
        if ($request->fir_vencimiento) {
            $empresa->fir_vencimiento = trim($request->fir_vencimiento);
        }

        $carpetaprincipal = 'archivos';
        if ($request->hasFile('logo')) {
            $nombre_imagen = $request->file('logo')->getClientOriginalName();
            $empresa->logo = 'storage/' . $carpetaprincipal . '/logo/' . $nombre_imagen;
            $request->file('logo')->storeAs($carpetaprincipal . '/logo/', $nombre_imagen, 'public');
        }
        if ($request->hasFile('firma')) {
            $nombre_firma = $request->file('firma')->getClientOriginalName();
            $empresa->firma = 'storage/' . $carpetaprincipal . '/firma/' . $nombre_firma;
            $request->file('firma')->storeAs($carpetaprincipal . '/firma/', $nombre_firma, 'public');
        }
        $empresa->save();
    }
}
