<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetencionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retenciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('punto_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->date('fec_emision');
            $table->date('fec_autorizacion');
            $table->integer('num_secuencial');
            $table->tinyInteger('tip_emision');
            $table->tinyInteger('tip_ambiente');
            $table->string('cla_acceso', 49);
            $table->string('num_autorizacion', 49);
            $table->string('eje_fiscal', 7);
            $table->decimal('tot_retenido', 12, 2)->default(0);
            $table->string('estado', 3);
            $table->string('respuesta', 50)->nullable();
            $table->timestamps();

            $table->foreign('punto_id')->references('id')->on('puntos_emision');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retenciones');
    }
}
