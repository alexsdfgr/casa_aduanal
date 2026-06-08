<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Partidas ──────────────────────────────────────────
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->integer('secuencia')->default(1);
            $table->string('fraccion_arancelaria', 10);
            $table->string('subdivision', 5)->nullable();
            $table->string('vinculacion', 2)->default('0');
            $table->integer('metodo_valoracion')->default(1);
            $table->string('pais_origen_destino', 5)->nullable();
            $table->string('pais_vendedor_comprador', 5)->nullable();
            $table->string('umc', 5)->nullable();
            $table->decimal('cantidad_umc', 18, 3)->default(0);
            $table->string('umt', 5)->nullable();
            $table->decimal('cantidad_umt', 18, 3)->nullable();
            $table->decimal('precio_valor_comercial', 18, 5)->default(0);
            $table->string('precio_origen_destino', 3)->nullable();
            $table->text('descripcion');
            $table->decimal('val_aduana_usd', 18, 2)->default(0);
            $table->decimal('importe_precio_pagado', 18, 2)->default(0);
            $table->decimal('precio_unitario', 18, 5)->default(0);
            $table->decimal('valor_agregado', 18, 2)->default(0);
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('codigo_producto', 100)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // ── Contribuciones por partida ────────────────────────
        Schema::create('contribuciones_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained('partidas')->onDelete('cascade');
            $table->string('contribucion', 10);
            $table->string('tipo_tasa', 5)->nullable();
            $table->decimal('tasa', 12, 5)->default(0);
            $table->integer('fp')->default(1);
            $table->decimal('importe', 18, 2)->default(0);
            $table->timestamps();
        });

        // ── Identificadores por partida ───────────────────────
        Schema::create('identificadores_partida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partida_id')->constrained('partidas')->onDelete('cascade');
            $table->string('clave', 5);
            $table->string('complemento1', 100)->nullable();
            $table->string('complemento2', 100)->nullable();
            $table->string('complemento3', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('identificadores_partida');
        Schema::dropIfExists('contribuciones_partida');
        Schema::dropIfExists('partidas');
    }
};
