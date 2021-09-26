<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tarifas')->insert([
            'impuesto_id' => 1,
            'nombre' => mb_strtoupper(trim('0%')),
            'codigo' => 0,
            'valor' => 0,
        ]);
        DB::table('tarifas')->insert([
            'impuesto_id' => 1,
            'nombre' => mb_strtoupper(trim('12%')),
            'codigo' => 2,
            'valor' => 12,
        ]);
        DB::table('tarifas')->insert([
            'impuesto_id' => 1,
            'nombre' => mb_strtoupper(trim('14%')),
            'codigo' => 3,
            'valor' => 14,
        ]);
        DB::table('tarifas')->insert([
            'impuesto_id' => 1,
            'nombre' => mb_strtoupper(trim('NO OBJETO DE IMPUESTO')),
            'codigo' => 6,
            'valor' => 0,
        ]);
        DB::table('tarifas')->insert([
            'impuesto_id' => 1,
            'nombre' => mb_strtoupper(trim('EXENTO DE I.V.A.')),
            'codigo' => 7,
            'valor' => 0,
        ]);
    }
}
