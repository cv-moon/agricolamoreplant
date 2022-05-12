<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_factura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factura_id');
            $table->unsignedBigInteger('presentacion_id');
            $table->decimal('det_cantidad', 12, 2);
            $table->decimal('det_precio', 12, 3);
            $table->decimal('det_descuento', 12, 2);
            $table->decimal('det_total', 12, 2);
            $table->string('det_campo', 300)->nullable();

            $table->foreign('factura_id')->references('id')->on('facturas');
            $table->foreign('presentacion_id')->references('id')->on('presentaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_factura');
    }
}
