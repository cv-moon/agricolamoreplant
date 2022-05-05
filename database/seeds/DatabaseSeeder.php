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
            AnioSeeder::class,
            MesSeeder::class,
            TipoComprobanteSeeder::class,
            TipoEstadoSeeder::class,
            TipoIdentificacionSeeder::class,
            ImpuestoAgregadoSeeder::class,
            ImpuestoRetencionSeeder::class,
            TarifaAgregadoSeeder::class,
            TarifaRetencionSeeder::class,
            TipoAmbienteSeeder::class,
            FormaPagoSeeder::class
        ]);
    }
}
