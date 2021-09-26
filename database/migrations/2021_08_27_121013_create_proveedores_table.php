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
            $table->string('nombre', 300);
            $table->string('tip_identificacion', 3);
            $table->string('num_identificacion', 13)->unique();
            $table->string('direccion', 300)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telefonos', 50)->nullable();
            $table->string('nom_contacto', 300)->nullable();
            $table->string('tel_contacto', 50)->nullable();
            $table->timestamps();
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
