<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $roles = Rol::select(
            'id',
            'nombre',
            'descripcion'
        )->get();
        return $roles;
    }
}
