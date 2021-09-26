<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comprobantes')->insert([
            'nombre' => 'FACTURA',
            'codigo' => '01',
        ]);
        DB::table('comprobantes')->insert([
            'nombre' => 'LIQUIDACIÓN DE COMPRA DE BIENES Y PRESTACIÓN DE SERVICIOS',
            'codigo' => '03',
        ]);
        DB::table('comprobantes')->insert([
            'nombre' => 'NOTA DE CRÉDITO',
            'codigo' => '04',
        ]);
        DB::table('comprobantes')->insert([
            'nombre' => 'NOTA DE DÉBITO',
            'codigo' => '05',
        ]);
        DB::table('comprobantes')->insert([
            'nombre' => 'GUÍA DE REMISIÓN',
            'codigo' => '06',
        ]);
        DB::table('comprobantes')->insert([
            'nombre' => 'COMPROBANTE DE RETENCIÓN',
            'codigo' => '07',
        ]);
    }
}
