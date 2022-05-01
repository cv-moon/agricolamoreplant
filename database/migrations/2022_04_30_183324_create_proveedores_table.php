<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tip_identificacion_id');
            $table->string('raz_social', 300);
            $table->string('num_identificacion', 13)->unique();
            $table->string('localidad', 100)->nullable();
            $table->string('direccion', 300)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('tel_uno', 10);
            $table->string('tel_dos', 10)->nullable();
            $table->timestamps();

            $table->foreign('tip_identificacion_id')->references('id')->on('tip_identificaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
