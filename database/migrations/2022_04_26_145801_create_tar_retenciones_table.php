<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarRetencionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tar_retenciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imp_retencione_id');
            $table->string('nombre', 300);
            $table->char('codigo', 5);
            $table->decimal('valor', 3, 2);

            $table->foreign('imp_retencione_id')->references('id')->on('imp_retenciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_retenciones');
    }
}
