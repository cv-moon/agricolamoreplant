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
            $table->unsignedBigInteger('tar_agregado_id');
            $table->string('nombre', 300)->unique();
            $table->string('composicion', 300)->nullable();
            $table->decimal('pre_compra', 15, 3)->default(0);
            $table->decimal('por_descuento', 5, 2)->default(0);
            $table->decimal('mar_utilidad', 5, 2)->default(20);
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('tar_agregado_id')->references('id')->on('tar_agregados');
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
