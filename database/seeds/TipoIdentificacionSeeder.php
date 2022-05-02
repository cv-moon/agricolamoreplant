<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoIdentificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tip_identificaciones')->insert([
            'nombre' => 'RUC',
            'codigo' => '04',
        ]);
        DB::table('tip_identificaciones')->insert([
            'nombre' => 'CEDULA',
            'codigo' => '05',
        ]);
        DB::table('tip_identificaciones')->insert([
            'nombre' => 'PASAPORTE',
            'codigo' => '06',
        ]);
        DB::table('tip_identificaciones')->insert([
            'nombre' => 'VENTA A CONSUMIDOR FINAL',
            'codigo' => '07',
        ]);
        DB::table('tip_identificaciones')->insert([
            'nombre' => 'IDENTIFICACION DEL EXTERIOR',
            'codigo' => '08',
        ]);
    }
}
