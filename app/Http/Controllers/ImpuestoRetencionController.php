<?php

namespace App\Http\Controllers;

use App\Models\ImpuestoRetencion;
use Illuminate\Http\Request;

class ImpuestoRetencionController extends Controller
{
    public function index()
    {
        $imp_retenciones = ImpuestoRetencion::get();
        return $imp_retenciones;
    }
}
