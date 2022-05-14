<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonosOdtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos_odt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_trabajo_id');
            $table->integer('num_secuencial');
            $table->decimal('val_abono', 12, 2);
            $table->date('fec_abono');
            $table->string('observaciones', 150);
            $table->boolean('estado')->default(1);

            $table->foreign('orden_trabajo_id')->references('id')->on('ordenes_trabajo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonos_odt');
    }
}
