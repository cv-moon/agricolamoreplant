<?php

namespace App\Http\Controllers;

use App\Models\TipoIdentificacion;
use Illuminate\Http\Request;

class TipoIdentificacionController extends Controller
{
    public function identityProvider()
    {
        $tip_identificaciones = TipoIdentificacion::select(
            'id',
            'nombre',
            'codigo'
        )
            ->where('nombre', '!=', 'VENTA A CONSUMIDOR FINAL')
            ->get();
        return $tip_identificaciones;
    }

    public function identityClient()
    {
        $tip_identificaciones = TipoIdentificacion::select('id', 'nombre', 'codigo')
            ->get();
        return $tip_identificaciones;
    }

    public function identityEmployees()
    {
        $tip_identificaciones = TipoIdentificacion::select('id', 'nombre', 'codigo')
            ->where('nombre', '!=', 'VENTA A CONSUMIDOR FINAL')
            ->where('nombre', '!=', 'RUC')
            ->get();
        return $tip_identificaciones;
    }
}
