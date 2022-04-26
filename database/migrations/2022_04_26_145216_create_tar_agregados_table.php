<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarAgregadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tar_agregados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imp_agregado_id');
            $table->string('nombre', 300);
            $table->char('codigo', 5);
            $table->decimal('valor', 3, 2);

            $table->foreign('imp_agregado_id')->references('id')->on('imp_agregados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_agregados');
    }
}
