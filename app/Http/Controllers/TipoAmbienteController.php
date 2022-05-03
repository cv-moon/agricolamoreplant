<?php

namespace App\Http\Controllers;

use App\Models\TipoAmbiente;
use Illuminate\Http\Request;

class TipoAmbienteController extends Controller
{
    public function index()
    {
        $tip_ambiente = TipoAmbiente::select('id', 'nombre', 'codigo')
            ->get();
        return $tip_ambiente;
    }
}
