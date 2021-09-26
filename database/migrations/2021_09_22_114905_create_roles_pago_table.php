<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mes_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('ven_mensual', 12, 2);
            $table->tinyInteger('por_venta');
            $table->decimal('tot_recibir', 12, 2);
            $table->char('estado', 1)->nullable();
            $table->timestamps();

            $table->foreign('mes_id')->references('id')->on('meses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_pago');
    }
}
