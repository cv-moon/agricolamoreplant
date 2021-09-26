<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::join('users', 'empleados.id', 'users.id')
            ->join('roles', 'users.rol_id', 'roles.id')
            ->select(
                'users.id',
                'empleados.nombres',
                'empleados.apellidos',
                'empleados.tip_identificacion',
                'empleados.num_identificacion',
                'empleados.salario',
                'empleados.fec_ingreso',
                'empleados.fec_salida',
                'users.estado',
                'roles.nombre as rol'
            )
            ->orderBy('empleados.apellidos', 'asc')
            ->get();
        return $empleados;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->rol_id = trim($request->rol_id);
            $user->usuario = mb_strtolower(trim($request->usuario));
            $user->email = mb_strtolower(trim($request->email));
            $user->password = Hash::make(trim($request->password));
            $user->save();

            $empleado = new Empleado();
            $empleado->id = $user->id;
            $empleado->nombres = mb_strtoupper(trim($request->nombres));
            $empleado->apellidos = mb_strtoupper(trim($request->apellidos));
            $empleado->tip_identificacion = mb_strtoupper(trim($request->tip_identificacion));
            $empleado->num_identificacion = trim($request->num_identificacion);
            $empleado->direccion = mb_strtoupper(trim($request->direccion));
            $empleado->telefonos = mb_strtoupper(trim($request->telefonos));
            $empleado->salario = trim($request->salario);
            if ($request->fec_ingreso) {
                $empleado->fec_ingreso = trim($request->fec_ingreso);
            }
            if ($request->salida) {
                $empleado->salida = trim($request->salida);
            }
            $empleado->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->rol_id = trim($request->rol_id);
            $user->usuario = mb_strtolower(trim($request->usuario));
            $user->email = mb_strtolower(trim($request->email));
            $user->password = Hash::make(trim($request->password));
            $user->save();

            $empleado = Empleado::findOrFail($user->id);
            $empleado->id = $user->id;
            $empleado->nombres = mb_strtoupper(trim($request->nombres));
            $empleado->apellidos = mb_strtoupper(trim($request->apellidos));
            $empleado->tip_identificacion = mb_strtoupper(trim($request->tip_identificacion));
            $empleado->num_identificacion = trim($request->num_identificacion);
            $empleado->direccion = mb_strtoupper(trim($request->direccion));
            $empleado->telefonos = mb_strtoupper(trim($request->telefonos));
            $empleado->salario = trim($request->salario);
            if ($request->fec_ingreso) {
                $empleado->fec_ingreso = trim($request->fec_ingreso);
            }
            if ($request->salida) {
                $empleado->salida = trim($request->salida);
            }
            $empleado->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function detail(Request $request)
    {
        $user = User::join('empleados', 'users.id', 'empleados.id')
            ->select(
                'users.id',
                'users.email',
                'users.usuario',
                'users.estado',
                'users.rol_id',
                'empleados.nombres',
                'empleados.apellidos',
                'empleados.tip_identificacion',
                'empleados.num_identificacion',
                'empleados.direccion',
                'empleados.telefonos',
                'empleados.salario',
                'empleados.fec_ingreso',
                'empleados.fec_salida'
            )
            ->where('users.id', '=', $request->id)
            ->first();
        return $user;
    }

    public function activate(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->estado = 1;
        $user->save();
    }

    public function deactivate(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->estado = 0;
        $user->save();
    }
}
