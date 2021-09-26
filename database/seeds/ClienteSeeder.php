<?php

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cliente = new Cliente();
        $cliente->identificacion_id = 4;
        $cliente->nombre = mb_strtoupper(trim('CONSUMIDOR FINAL'));
        $cliente->num_identificacion = trim('9999999999999');
        $cliente->direccion = mb_strtoupper(trim('999999999999999999999'));
        $cliente->telefonos = mb_strtoupper(trim('9999999999'));
        $cliente->email = mb_strtolower(trim('aaaa@aa.com'));
        $cliente->descuento = trim(0);
        $cliente->lim_credito = trim(0);
        $cliente->save();
    }
}
