<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meses')->insert([
            'nombre' => 'ENERO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'FEBRERO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'MARZO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'ABRIL',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'MAYO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'JUNIO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'JULIO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'AGOSTO',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'SEPTIEMBRE',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'OCTUBRE',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'NOVIEMBRE',
        ]);
        DB::table('meses')->insert([
            'nombre' => 'DICIEMBRE',
        ]);
    }
}
