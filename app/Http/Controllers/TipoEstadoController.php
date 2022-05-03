<?php

namespace App\Http\Controllers;

use App\Models\TipoEstado;
use Illuminate\Http\Request;

class TipoEstadoController extends Controller
{
    public function index()
    {
        $tip_estados = TipoEstado::select(
            'id',
            'nombre',
            'codigo'
        )
            ->orderBy('nombre', 'asc')
            ->get();
        return $tip_estados;
    }
}
