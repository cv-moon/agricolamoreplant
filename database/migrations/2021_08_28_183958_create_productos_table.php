<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('unidad_id');
            $table->unsignedBigInteger('tarifa_id');
            $table->string('nombre', 300)->unique();
            $table->string('cod_principal', 15)->unique();
            $table->string('cod_auxiliar', 15)->unique()->nullable();
            $table->string('composicion', 300)->nullable();
            $table->decimal('pre_venta', 12, 3);
            $table->decimal('por_descuento', 12, 3)->default(0);
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('unidad_id')->references('id')->on('unidades');
            $table->foreign('tarifa_id')->references('id')->on('tarifas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
