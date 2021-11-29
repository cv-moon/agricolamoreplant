<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('punto_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('for_pago_id');
            $table->date('fec_emision');
            $table->date('fec_autorizacion');
            $table->integer('num_secuencial');
            $table->tinyInteger('tip_emision');
            $table->tinyInteger('tip_ambiente');
            $table->string('cla_acceso', 49);
            $table->string('num_autorizacion', 49);
            $table->decimal('sub_sin_imp', 12, 2)->default(0);
            $table->decimal('sub_iva', 12, 2)->default(0);
            $table->decimal('sub_0', 12, 2)->default(0);
            $table->decimal('sub_no_iva', 12, 2)->default(0);
            $table->decimal('sub_exento', 12, 2)->default(0);
            $table->decimal('tot_descuento', 12, 2)->default(0);
            $table->decimal('val_ice', 12, 2)->default(0);
            $table->decimal('val_irbpnr', 12, 2)->default(0);
            $table->decimal('val_iva', 12, 2)->default(0);
            $table->decimal('val_propina', 12, 2)->default(0);
            $table->decimal('val_total', 12, 2)->default(0);
            $table->char('for_pago', 1);
            $table->string('estado', 3);
            $table->string('respuesta', 50)->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('punto_id')->references('id')->on('puntos_emision');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('for_pago_id')->references('id')->on('formas_pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
