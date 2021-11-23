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
        DB::table('impuestos_retencion')->insert([
            'nombre' => 'RENTA',
            'codigo' => '1',
        ]);
        DB::table('impuestos_retencion')->insert([
            'nombre' => 'IVA',
            'codigo' => '2',
        ]);
        DB::table('impuestos_retencion')->insert([
            'nombre' => 'ISD',
            'codigo' => '6',
        ]);
    }
}
