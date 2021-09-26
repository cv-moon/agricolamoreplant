<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::select(
            'id',
            'nombre',
            'tip_identificacion',
            'num_identificacion',
            'direccion',
            'telefonos',
            'email',
            'nom_contacto',
            'tel_contacto'
        )
            ->orderBy('nombre', 'asc')
            ->get();
        return $proveedores;
    }

    public function store(Request $request)
    {
        $proveedor = new Proveedor();
        $proveedor->nombre = mb_strtoupper(trim($request->nombre));
        $proveedor->tip_identificacion = mb_strtoupper(trim($request->tip_identificacion));
        $proveedor->num_identificacion = trim($request->num_identificacion);
        $proveedor->direccion = mb_strtoupper(trim($request->direccion));
        $proveedor->telefonos = trim($request->telefonos);
        $proveedor->email = mb_strtolower(trim($request->email));
        $proveedor->nom_contacto = mb_strtoupper(trim($request->nom_contacto));
        $proveedor->tel_contacto = trim($request->tel_contacto);
        $proveedor->save();
    }

    public function update(Request $request)
    {
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->nombre = mb_strtoupper(trim($request->nombre));
        $proveedor->tip_identificacion = mb_strtoupper(trim($request->tip_identificacion));
        $proveedor->num_identificacion = trim($request->num_identificacion);
        $proveedor->direccion = mb_strtoupper(trim($request->direccion));
        $proveedor->telefonos = trim($request->telefonos);
        $proveedor->email = mb_strtolower(trim($request->email));
        $proveedor->nom_contacto = mb_strtoupper(trim($request->nom_contacto));
        $proveedor->tel_contacto = trim($request->tel_contacto);
        $proveedor->save();
    }

    public function detail(Request $request)
    {
        $proveedor = Proveedor::select(
            'id',
            'nombre',
            'tip_identificacion',
            'num_identificacion',
            'direccion',
            'telefonos',
            'email',
            'nom_contacto',
            'tel_contacto'
        )
            ->where('id',  $request->id)
            ->first();
        return $proveedor;
    }

    public function find(Request $request)
    {
        $proveedores = Proveedor::select('id', 'nombre', 'num_identificacion')
            ->where('nombre', 'like', '%' . $request->proveedor . '%')
            ->orWhere('num_identificacion', 'like', '%' . $request->proveedor . '%')
            ->orderBy('nombre', 'asc')
            ->get();
        return $proveedores;
    }
}
