<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('tip_identificacion_id');
            $table->string('nom_primero', 40);
            $table->string('nom_segundo', 40);
            $table->string('ape_paterno', 40);
            $table->string('ape_materno', 40);
            $table->string('num_identificacion', 13)->unique();
            $table->string('direccion', 300)->nullable();
            $table->string('telefonos', 100)->nullable();
            $table->decimal('salario', 12, 2);
            $table->date('fec_ingreso')->nullable();
            $table->date('fec_salida')->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users');
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
        Schema::dropIfExists('empleados');
    }
}
