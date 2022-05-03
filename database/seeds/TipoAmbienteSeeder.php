<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoAmbienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tip_ambientes')->insert([
            'nombre' => 'PRUEBAS',
            'codigo' => '1',
        ]);
        DB::table('tip_ambientes')->insert([
            'nombre' => 'PRODUCCIÓN',
            'codigo' => '2',
        ]);
    }
}
