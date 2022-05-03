<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifaRetencionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('10%')),
            'codigo' => 9,
            'valor' => 10,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('20%')),
            'codigo' => 10,
            'valor' => 20,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('30%')),
            'codigo' => 1,
            'valor' => 30,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('50%')),
            'codigo' => 11,
            'valor' => 50,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('70%')),
            'codigo' => 2,
            'valor' => 70,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('100%')),
            'codigo' => 3,
            'valor' => 100,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('RETENCIÓN EN CERO')),
            'codigo' => 7,
            'valor' => 0,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 2,
            'nombre' => mb_strtoupper(trim('NO PROCEDE RETENCIÓN')),
            'codigo' => 8,
            'valor' => 0,
        ]);
        DB::table('tar_retenciones')->insert([
            'impuesto_id' => 3,
            'nombre' => mb_strtoupper(trim('5%')),
            'codigo' => 4580,
            'valor' => 5,
        ]);
    }
}
