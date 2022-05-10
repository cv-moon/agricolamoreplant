<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::join('tip_identificaciones', 'proveedores.tip_identificacion_id', 'tip_identificaciones.id')
            ->select(
                'proveedores.id',
                'proveedores.raz_social',
                'proveedores.num_identificacion',
                'proveedores.tel_uno',
                'proveedores.tel_dos',
                'tip_identificaciones.nombre'
            )
            ->orderBy('proveedores.raz_social', 'asc')
            ->get();
        return $proveedores;
    }

    public function store(Request $request)
    {
        $proveedor = new Proveedor();
        $proveedor->tip_identificacion_id = trim($request->tip_identificacion_id);
        $proveedor->raz_social = mb_strtoupper(trim($request->raz_social));
        $proveedor->num_identificacion = trim($request->num_identificacion);
        $proveedor->localidad = mb_strtoupper(trim($request->localidad));
        $proveedor->direccion = mb_strtoupper(trim($request->direccion));
        $proveedor->email = mb_strtolower(trim($request->email));
        $proveedor->tel_uno = trim($request->tel_uno);
        $proveedor->tel_dos = trim($request->tel_dos);
        $proveedor->save();
    }

    public function update(Request $request)
    {
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->tip_identificacion_id = trim($request->tip_identificacion_id);
        $proveedor->raz_social = mb_strtoupper(trim($request->raz_social));
        $proveedor->num_identificacion = trim($request->num_identificacion);
        $proveedor->localidad = mb_strtoupper(trim($request->localidad));
        $proveedor->direccion = mb_strtoupper(trim($request->direccion));
        $proveedor->email = mb_strtolower(trim($request->email));
        $proveedor->tel_uno = trim($request->tel_uno);
        $proveedor->tel_dos = trim($request->tel_dos);
        $proveedor->save();
    }

    public function detail(Request $request)
    {
        $proveedor = Proveedor::select(
            'id',
            'tip_identificacion_id',
            'raz_social',
            'num_identificacion',
            'localidad',
            'direccion',
            'email',
            'tel_uno',
            'tel_dos'
        )
            ->where('id',  $request->id)
            ->first();
        return $proveedor;
    }

    public function find(Request $request)
    {
        $proveedores = Proveedor::select(
            'id',
            'raz_social',
            'num_identificacion'
        )
            ->where('raz_social', 'like', '%' . $request->proveedor . '%')
            ->orWhere('num_identificacion', 'like', '%' . $request->proveedor . '%')
            ->orderBy('raz_social', 'asc')
            ->get();
        return $proveedores;
    }
}
