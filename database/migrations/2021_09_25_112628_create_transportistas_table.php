<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportistas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identificacion_id');
            $table->string('nombre', 300);
            $table->string('num_identificacion', 13)->unique();
            $table->string('placa', 9);
            $table->string('direccion', 300)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telefonos', 50)->nullable();
            $table->timestamps();

            $table->foreign('identificacion_id')->references('id')->on('identificaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transportistas');
    }
}
