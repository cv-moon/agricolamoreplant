<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presentacion_id');
            $table->unsignedBigInteger('establecimiento_id');
            $table->integer('min_stock')->default(1);
            $table->decimal('dis_stock', 15, 2);
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->foreign('presentacion_id')->references('id')->on('presentaciones');
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
}
