<?php

namespace App\Http\Controllers;

use App\Models\Identificacion;
use Illuminate\Http\Request;

class IdentificacionController extends Controller
{
    public function index()
    {
        $identificaciones = Identificacion::where('nombre', '!=', 'VENTA A CONSUMIDOR FINAL')
            ->get();
        return $identificaciones;
    }
}
