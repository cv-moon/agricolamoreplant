<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_compra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compra_id');
            $table->unsignedBigInteger('presentacion_id');
            $table->integer('cantidad');
            $table->date('fec_vencimiento')->nullable();
            $table->decimal('precio', 12, 3);
            $table->decimal('descuento', 12, 3);

            $table->foreign('compra_id')->references('id')->on('compras');
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
        Schema::dropIfExists('detalles_compra');
    }
}
