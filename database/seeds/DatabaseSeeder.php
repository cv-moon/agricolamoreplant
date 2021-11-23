<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolSeeder::class,
            UserSeeder::class,
            ImpuestoSeeder::class,
            TarifaSeeder::class,
            IdentificacionSeeder::class,
            ComprobanteSeeder::class,
            ClienteSeeder::class,
            FormaPagoSeeder::class,
            MesSeeder::class,
            ImpuestoRetencionSeeder::class,
            TarifaRetencionSeeder::class
        ]);
    }
}
