<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    public function index(Request $request)
    {
        $egresos = Egreso::join('users', 'egresos.user_id', 'users.id')
            ->select(
                'egresos.id',
                'egresos.arqueo_id',
                'egresos.descripcion',
                'egresos.valor',
                'users.usuario as beneficiario'
            )
            ->where('egresos.arqueo_id', $request->arqueo)
            ->get();
        $suma = Egreso::join('arqueos', 'egresos.arqueo_id', 'arqueos.id')
            ->select(
                'egresos.arqueo_id',
                'egresos.descripcion',
                'egresos.valor'
            )
            ->where('egresos.arqueo_id', $request->arqueo)
            ->get()
            ->sum('valor');
        return ['egresos' => $egresos, 'suma' => $suma];
    }

    public function store(Request $request)
    {
        $gasto = new Egreso();
        $gasto->arqueo_id = trim($request->arqueo_id);
        $gasto->user_id = trim($request->user_id);
        $gasto->descripcion = mb_strtoupper(trim($request->descripcion));
        $gasto->valor = trim($request->valor);
        $gasto->save();
    }

    public function drop(Request $request)
    {
        Egreso::destroy($request->id);
    }
}
