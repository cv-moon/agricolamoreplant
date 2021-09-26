<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesRolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_rol', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->char('for_pago', 1);
            $table->decimal('val_pago', 12, 2);
            $table->string('observaciones', 300)->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('roles_pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_rol');
    }
}
