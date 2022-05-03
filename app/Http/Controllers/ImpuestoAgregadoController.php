<?php

namespace App\Http\Controllers;

use App\Models\ImpuestoAgregado;
use Illuminate\Http\Request;

class ImpuestoAgregadoController extends Controller
{
    public function index()
    {
        $imp_agregados = ImpuestoAgregado::get();
        return $imp_agregados;
    }
}
