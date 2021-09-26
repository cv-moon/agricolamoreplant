<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArqueosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arqueos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('punto_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('mon_apertura', 12, 2)->nullable();
            $table->dateTime('fec_cierre')->nullable();
            $table->decimal('mon_cierre', 12, 2)->nullable();
            $table->decimal('tot_efectivo', 12, 2)->nullable();
            $table->decimal('tot_credito', 12, 2)->nullable();
            $table->decimal('tot_egreso', 12, 2)->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('punto_id')->references('id')->on('puntos_emision');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arqueos');
    }
}
