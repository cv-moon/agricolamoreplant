<?php

namespace App\Http\Controllers;

use App\Models\Mes;
use Illuminate\Http\Request;

class MesController extends Controller
{
    public function index()
    {
        $meses = Mes::where('id', date('m'))
            ->get();
        return $meses;
    }
}
