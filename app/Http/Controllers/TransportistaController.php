<?php

namespace App\Http\Controllers;

use App\Models\Transportista;
use Illuminate\Http\Request;

class TransportistaController extends Controller
{
    public function index()
    {
        $transportistas = Transportista::join('identificaciones', 'transportistas.identificacion_id', 'identificaciones.id')
            ->select(
                'transportistas.id',
                'transportistas.nombre',
                'transportistas.num_identificacion',
                'transportistas.placa',
                'transportistas.direccion',
                'transportistas.telefonos',
                'transportistas.email',
                'identificaciones.nombre as tip_identificacion'
            )
            ->orderBy('nombre', 'asc')
            ->get();
        return $transportistas;
    }

    public function store(Request $request)
    {
        $transportista = new Transportista();
        $transportista->identificacion_id = trim($request->identificacion_id);
        $transportista->nombre = mb_strtoupper(trim($request->nombre));
        $transportista->num_identificacion = trim($request->num_identificacion);
        $transportista->placa = mb_strtoupper(trim($request->placa));
        $transportista->direccion = mb_strtoupper(trim($request->direccion));
        $transportista->telefonos = trim($request->telefonos);
        $transportista->email = mb_strtolower(trim($request->email));
        $transportista->save();
    }

    public function update(Request $request)
    {
        $transportista = Transportista::findOrFail($request->id);
        $transportista->identificacion_id = trim($request->identificacion_id);
        $transportista->nombre = mb_strtoupper(trim($request->nombre));
        $transportista->num_identificacion = trim($request->num_identificacion);
        $transportista->placa = mb_strtoupper(trim($request->placa));
        $transportista->direccion = mb_strtoupper(trim($request->direccion));
        $transportista->telefonos = trim($request->telefonos);
        $transportista->email = mb_strtolower(trim($request->email));
        $transportista->save();
    }

    public function detail(Request $request)
    {
        $transportista = Transportista::join('identificaciones', 'transportistas.identificacion_id', 'identificaciones.id')
            ->select(
                'transportistas.id',
                'transportistas.identificacion_id',
                'transportistas.nombre',
                'transportistas.num_identificacion',
                'transportistas.placa',
                'transportistas.direccion',
                'transportistas.telefonos',
                'transportistas.email',
            )
            ->where('transportistas.id', $request->id)
            ->first();
        return $transportista;
    }

    public function find(Request $request)
    {
        $transportistas = Transportista::select('id', 'nombre', 'num_identificacion')
            ->where('nombre', 'like', '%' . $request->tr . '%')
            ->orWhere('num_identificacion', 'like', '%' . $request->tr . '%')
            ->orderBy('nombre', 'asc')
            ->get();
        return $transportistas;
    }
}
