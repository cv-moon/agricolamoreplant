<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->decimal('saldo', 12, 2);
            $table->decimal('abonos', 12, 2);
            $table->integer('dias_credito');
            $table->date('fec_limite');
            $table->string('observaciones', 150)->nullable();
            $table->char('estado', 1);
            $table->timestamps();

            $table->foreign('id')->references('id')->on('facturas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditos');
    }
}
