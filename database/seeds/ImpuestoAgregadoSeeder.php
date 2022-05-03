<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImpuestoAgregadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('imp_agregados')->insert([
            'nombre' => 'IVA',
            'codigo' => '2',
        ]);
        DB::table('imp_agregados')->insert([
            'nombre' => 'ICE',
            'codigo' => '3',
        ]);
        DB::table('imp_agregados')->insert([
            'nombre' => 'IRBPNR',
            'codigo' => '5',
        ]);
    }
}
