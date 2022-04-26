<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntosEmisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puntos_emision', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('pun_codigo');
            $table->string('nombre', 100);
            $table->integer('sec_factura')->nullable();
            $table->integer('sec_retencion')->nullable();
            $table->integer('sec_gui_remision')->nullable();
            $table->integer('sec_orden_trabajo')->nullable();
            $table->integer('sec_recibo_cobro')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('puntos_emision');
    }
}
