<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('unidad_id');
            $table->string('cod_principal', 15)->unique();
            $table->string('cod_auxiliar', 15)->unique()->nullable();
            $table->integer('presentacion');
            $table->decimal('pre_venta', 15, 3)->default(0);
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('unidad_id')->references('id')->on('unidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presentaciones');
    }
}
