<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('formas_pago')->insert([
            'nombre' => 'SIN UTILIZACIÓN DEL SISTEMA FINANCIERO',
            'codigo' => '01',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'COMPENSACIÓN DE DEUDAS',
            'codigo' => '15',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'TARJETA DE DÉBITO',
            'codigo' => '16',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'DINERO ELECTRÓNICO',
            'codigo' => '17',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'TARJETA PREPAGO',
            'codigo' => '18',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'TARJETA DE CRÉDITO',
            'codigo' => '19',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO',
            'codigo' => '20',
        ]);
        DB::table('formas_pago')->insert([
            'nombre' => 'ENDOSO DE TÍTULOS',
            'codigo' => '21',
        ]);
    }
}
