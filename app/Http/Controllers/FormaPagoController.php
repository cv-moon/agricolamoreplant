<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;
use Illuminate\Http\Request;

class FormaPagoController extends Controller
{
    public function index()
    {
        $formas = FormaPago::select('id', 'nombre', 'codigo')
            ->get();
        return $formas;
    }
}
