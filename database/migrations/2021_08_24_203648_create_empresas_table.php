<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('raz_social', 300);
            $table->string('ruc', 13)->unique();
            $table->string('direccion', 300);
            $table->string('telefonos', 100);
            $table->string('url', 100);
            $table->string('logo', 100)->nullable();
            $table->string('cont_resolucion', 5)->nullable();
            $table->boolean('obli_contabilidad')->default(0);
            $table->boolean('reg_microempresa')->default(0);
            $table->boolean('age_retencion')->default(0);
            $table->string('firma', 100)->nullable();
            $table->string('fir_clave', 100)->nullable();
            $table->date('fir_vencimiento')->nullable();
            $table->boolean('tip_ambiente')->default(0);
            $table->boolean('tip_emision')->default(1);
            $table->string('corr_servidor', 50)->nullable();
            $table->integer('corr_puerto')->nullable();
            $table->string('corr_seguridad', 10)->nullable();
            $table->boolean('corr_autenticacion')->default(1);
            $table->string('corr_usuario', 100)->nullable();
            $table->string('corr_password', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
