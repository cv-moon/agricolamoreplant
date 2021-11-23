<?php

namespace App\Http\Controllers;

use App\Models\ImpuestoRetencion;
use Illuminate\Http\Request;

class ImpuestoRetencionController extends Controller
{
    public function index()
    {
        $impuestos = ImpuestoRetencion::get();
        return $impuestos;
    }
}
