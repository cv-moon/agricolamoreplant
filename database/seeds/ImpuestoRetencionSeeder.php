<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImpuestoRetencionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('imp_retenciones')->insert([
            'nombre' => 'RENTA',
            'codigo' => '1',
        ]);
        DB::table('imp_retenciones')->insert([
            'nombre' => 'IVA',
            'codigo' => '2',
        ]);
        DB::table('imp_retenciones')->insert([
            'nombre' => 'ISD',
            'codigo' => '6',
        ]);
    }
}
