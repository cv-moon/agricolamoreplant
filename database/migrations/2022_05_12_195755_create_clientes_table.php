<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tip_identificacion_id');
            $table->string('nombre', 100)->unique();
            $table->string('num_identificacion', 13)->unique();
            $table->string('direccion', 200)->nullable();
            $table->string('telefonos', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('lim_credito', 12, 2)->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
