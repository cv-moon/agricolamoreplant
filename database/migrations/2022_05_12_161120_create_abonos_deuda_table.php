<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonosDeudaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos_deuda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deuda_id');
            $table->decimal('val_abono', 12, 2);
            $table->date('fec_abono');
            $table->string('obs_abono', 300);
            $table->timestamps();

            $table->foreign('deuda_id')->references('id')->on('deudas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonos_deuda');
    }
}
