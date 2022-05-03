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
        DB::table('for_pagos')->insert([
            'nombre' => 'SIN UTILIZACIÓN DEL SISTEMA FINANCIERO',
            'codigo' => '01',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'COMPENSACIÓN DE DEUDAS',
            'codigo' => '15',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'TARJETA DE DÉBITO',
            'codigo' => '16',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'DINERO ELECTRÓNICO',
            'codigo' => '17',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'TARJETA PREPAGO',
            'codigo' => '18',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'TARJETA DE CRÉDITO',
            'codigo' => '19',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO',
            'codigo' => '20',
        ]);
        DB::table('for_pagos')->insert([
            'nombre' => 'ENDOSO DE TÍTULOS',
            'codigo' => '21',
        ]);
    }
}
