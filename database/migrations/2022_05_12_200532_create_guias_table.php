<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('punto_id');
            $table->unsignedBigInteger('transportista_id');
            $table->date('fec_emision');
            $table->date('fec_autorizacion');
            $table->integer('num_secuencial');
            $table->tinyInteger('tip_emision');
            $table->tinyInteger('tip_ambiente');
            $table->string('cla_acceso', 49);
            $table->string('num_autorizacion', 49);
            $table->date('fec_inicio');
            $table->date('fec_fin');
            $table->string('des_nombre', 300);
            $table->string('des_direccion', 300);
            $table->string('des_identificacion', 13);
            $table->string('motivo', 300);
            $table->string('ruta', 300);
            $table->string('observaciones', 255);
            $table->string('estado', 3);
            $table->string('respuesta', 50)->nullable();
            $table->timestamps();

            $table->foreign('factura_id')->references('id')->on('facturas');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('punto_id')->references('id')->on('puntos_emision');
            $table->foreign('transportista_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guias');
    }
}
