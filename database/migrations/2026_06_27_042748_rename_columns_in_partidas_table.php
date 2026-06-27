<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->renameColumn('secuencia', 'sec');
            $table->renameColumn('fraccion_arancelaria', 'fraccion');
            $table->renameColumn('subdivision', 'subd_nico');
            $table->renameColumn('vinculacion', 'vinc');
            $table->renameColumn('metodo_valoracion', 'met_val');
            $table->renameColumn('pais_origen_destino', 'p_od');
            $table->renameColumn('pais_vendedor_comprador', 'p_vc');
            $table->renameColumn('val_aduana_usd', 'val_adu_usd');
            $table->renameColumn('importe_precio_pagado', 'imp_precio_pag');
            $table->renameColumn('precio_unitario', 'precio_unit');
            $table->renameColumn('valor_agregado', 'val_agreg');
            $table->renameColumn('observaciones', 'obs_partida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->renameColumn('sec', 'secuencia');
            $table->renameColumn('fraccion', 'fraccion_arancelaria');
            $table->renameColumn('subd_nico', 'subdivision');
            $table->renameColumn('vinc', 'vinculacion');
            $table->renameColumn('met_val', 'metodo_valoracion');
            $table->renameColumn('p_od', 'pais_origen_destino');
            $table->renameColumn('p_vc', 'pais_vendedor_comprador');
            $table->renameColumn('val_adu_usd', 'val_aduana_usd');
            $table->renameColumn('imp_precio_pag', 'importe_precio_pagado');
            $table->renameColumn('precio_unit', 'precio_unitario');
            $table->renameColumn('val_agreg', 'valor_agregado');
            $table->renameColumn('obs_partida', 'observaciones');
        });
    }
};
