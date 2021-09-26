<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proveedor_id');
            $table->unsignedBigInteger('establecimiento_id');
            $table->string('tip_comprobante', 5);
            $table->string('num_comprobante', 17);
            $table->date('fec_emision');
            $table->decimal('sub_0', 12, 2);
            $table->decimal('sub_12', 12, 2);
            $table->decimal('tot_desc', 12, 2);
            $table->decimal('total', 12, 2);
            $table->char('for_pago', 1);
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
}
