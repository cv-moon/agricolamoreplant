<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonosCreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos_credito', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credito_id');
            $table->decimal('val_abono', 12, 2);
            $table->date('fec_abono');
            $table->string('observaciones', 150);

            $table->foreign('credito_id')->references('id')->on('creditos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonos_credito');
    }
}
