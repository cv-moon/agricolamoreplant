<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImpuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('impuestos')->insert([
            'nombre' => 'IVA',
            'codigo' => '2',
        ]);
        DB::table('impuestos')->insert([
            'nombre' => 'ICE',
            'codigo' => '3',
        ]);
        DB::table('impuestos')->insert([
            'nombre' => 'IRBPNR',
            'codigo' => '5',
        ]);
    }
}
