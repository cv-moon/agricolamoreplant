<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        if (Auth::user()->id === 1) {
            $user = User::select('id', 'email', 'usuario')
                ->where('id', Auth::user()->id)
                ->first();
        } else {
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
        }
        return $user;
    }

    public function changePass(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->password = Hash::make(trim($request->password));
        $user->save();
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->usuario = trim($request->usuario);
            $user->email = mb_strtolower(trim($request->email));
            $user->save();

            $empleado = Empleado::findOrFail($request->id);
            $empleado->nombres = mb_strtoupper(trim($request->nombres));
            $empleado->apellidos = mb_strtoupper(trim($request->apellidos));
            $empleado->tip_identificacion = mb_strtoupper(trim($request->tip_identificacion));
            $empleado->num_identificacion = trim($request->num_identificacion);
            $empleado->direccion = mb_strtoupper(trim($request->direccion));
            $empleado->telefonos = trim($request->telefonos);
            $empleado->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
