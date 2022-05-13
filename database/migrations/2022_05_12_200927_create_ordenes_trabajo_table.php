<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesTrabajoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('punto_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('for_pago_id');
            $table->integer('num_secuencial');
            $table->decimal('sub_sin_imp', 12, 2)->default(0);
            $table->decimal('tot_descuento', 12, 2)->default(0);
            $table->decimal('val_total', 12, 2)->default(0);
            $table->char('for_pago', 1);
            $table->string('estado', 3);
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('punto_id')->references('id')->on('puntos_emision');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('for_pago_id')->references('id')->on('for_pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
}
