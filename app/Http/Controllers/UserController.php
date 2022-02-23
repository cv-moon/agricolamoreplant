<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'usuario')
            ->get();
        return $users;
    }

    public function profile()
    {
        $user = User::join('empleados', 'users.id', 'empleados.id')
            ->select(
                'users.id',
                'users.email',
                'users.usuario',
                'empleados.nombres',
                'empleados.apellidos',
                'empleados.tip_identificacion',
                'empleados.num_identificacion',
                'empleados.direccion',
                'empleados.telefonos'
            )
            ->where('users.id', Auth::user()->id)
            ->first();
        return $user;
    }
}
