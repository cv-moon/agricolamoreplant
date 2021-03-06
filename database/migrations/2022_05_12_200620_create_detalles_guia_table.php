<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesGuiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_guia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guia_id');
            $table->unsignedBigInteger('presentacion_id');
            $table->decimal('det_cantidad', 12, 2);

            $table->foreign('guia_id')->references('id')->on('guias');
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
        Schema::dropIfExists('detalles_guia');
    }
}
