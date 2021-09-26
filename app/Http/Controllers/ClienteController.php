<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::join('identificaciones', 'clientes.identificacion_id', 'identificaciones.id')
            ->select(
                'clientes.id',
                'clientes.nombre',
                'clientes.num_identificacion',
                'clientes.direccion',
                'clientes.telefonos',
                'clientes.email',
                'clientes.lim_credito',
                'clientes.descuento',
                'identificaciones.nombre as tip_identificacion'
            )
            ->orderBy('nombre', 'asc')
            ->get();
        return $clientes;
    }

    public function store(Request $request)
    {
        $cliente = new Cliente();
        $cliente->identificacion_id = trim($request->identificacion_id);
        $cliente->nombre = mb_strtoupper(trim($request->nombre));
        $cliente->num_identificacion = trim($request->num_identificacion);
        $cliente->direccion = mb_strtoupper(trim($request->direccion));
        $cliente->telefonos = trim($request->telefonos);
        $cliente->email = mb_strtolower(trim($request->email));
        $cliente->lim_credito = trim($request->lim_credito);
        $cliente->descuento = trim($request->descuento);
        $cliente->save();
    }

    public function update(Request $request)
    {
        $cliente = Cliente::findOrFail($request->id);
        $cliente->identificacion_id = trim($request->identificacion_id);
        $cliente->nombre = mb_strtoupper(trim($request->nombre));
        $cliente->num_identificacion = trim($request->num_identificacion);
        $cliente->direccion = mb_strtoupper(trim($request->direccion));
        $cliente->telefonos = trim($request->telefonos);
        $cliente->email = mb_strtolower(trim($request->email));
        $cliente->lim_credito = trim($request->lim_credito);
        $cliente->descuento = trim($request->descuento);
        $cliente->save();
    }

    public function detail(Request $request)
    {
        $cliente = Cliente::join('identificaciones', 'clientes.identificacion_id', 'identificaciones.id')
            ->select(
                'clientes.id',
                'clientes.identificacion_id',
                'clientes.nombre',
                'clientes.num_identificacion',
                'clientes.direccion',
                'clientes.telefonos',
                'clientes.email',
                'clientes.lim_credito',
                'clientes.descuento'
            )
            ->where('clientes.id', '=', $request->id)
            ->first();
        return $cliente;
    }

    public function find(Request $request)
    {
        $clientes = Cliente::select('id', 'nombre', 'num_identificacion')
            ->where('nombre', 'like', '%' . $request->cli . '%')
            ->orWhere('num_identificacion', 'like', '%' . $request->cli . '%')
            ->orderBy('nombre', 'asc')
            ->get();
        return $clientes;
    }
}
