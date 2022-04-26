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
            $table->unsignedBigInteger('tip_ambiente_id');
            $table->string('raz_social', 300);
            $table->string('ruc', 13)->unique();
            $table->string('direccion', 300);
            $table->string('logo', 100)->nullable();
            $table->string('cont_resolucion', 5)->nullable();
            $table->boolean('obli_contabilidad')->default(0);
            $table->tinyInteger('regimen')->default(0);
            $table->boolean('age_retencion')->default(0);
            $table->boolean('tip_emision')->default(1);
            $table->string('firma', 100)->nullable();
            $table->string('fir_clave', 50)->nullable();
            $table->date('fir_vencimiento')->nullable();

            $table->foreign('tip_ambiente_id')->references('id')->on('tip_ambientes');
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
