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
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('tip_identificacion', 3);
            $table->string('num_identificacion', 13)->unique();
            $table->string('direccion', 300)->nullable();
            $table->string('telefonos')->nullable();
            $table->decimal('salario', 12, 2);
            $table->date('fec_ingreso')->nullable();
            $table->date('fec_salida')->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('users');
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
