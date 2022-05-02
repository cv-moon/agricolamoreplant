<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tip_estados')->insert([
            'nombre' => 'EN PROCESAMIENTO',
            'codigo' => 'PPR',
        ]);
        DB::table('tip_estados')->insert([
            'nombre' => 'AUTORIZADO',
            'codigo' => 'AUT',
        ]);
        DB::table('tip_estados')->insert([
            'nombre' => 'NO AUTORIZADO',
            'codigo' => 'NAT',
        ]);
    }
}
