<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesRetencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_retencion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retencion_id');
            $table->unsignedBigInteger('compra_id');
            $table->unsignedBigInteger('comprobante_id');
            $table->unsignedBigInteger('tarifas_retencion_id');
            $table->string('num_comprobante', 15);
            $table->date('fec_emi_comprobante');
            $table->decimal('bas_imponible', 12, 2);
            $table->decimal('val_retenido', 12, 2);

            $table->foreign('retencion_id')->references('id')->on('retenciones');
            $table->foreign('compra_id')->references('id')->on('compras');
            $table->foreign('comprobante_id')->references('id')->on('comprobantes');
            $table->foreign('tarifas_retencion_id')->references('id')->on('tarifas_retencion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_retencion');
    }
}
