<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasRetencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifas_retencion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('impuesto_id');
            $table->string('nombre', 250)->unique();
            $table->string('codigo', 6);
            $table->tinyInteger('valor');

            $table->foreign('impuesto_id')->references('id')->on('impuestos_retencion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarifas_retencion');
    }
}
