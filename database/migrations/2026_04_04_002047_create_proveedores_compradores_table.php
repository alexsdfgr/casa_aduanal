<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Proveedores / Compradores ─────────────────────────
        Schema::create('proveedores_compradores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->string('id_fiscal', 50)->nullable();
            $table->string('nombre', 200)->nullable();
            $table->string('domicilio', 400)->nullable();
            $table->enum('vinculacion', ['SI', 'NO'])->default('NO');
            $table->timestamps();
        });

        // ── Facturas ──────────────────────────────────────────
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->string('num_cfdi', 60)->nullable();
            $table->string('num_factura', 40)->nullable();
            $table->date('fecha')->nullable();
            $table->string('incoterm', 5)->nullable();
            $table->string('moneda_factura', 5)->default('USD');
            $table->decimal('val_moneda_fact', 18, 2)->default(0);
            $table->decimal('factor_moneda', 18, 8)->default(1);
            $table->decimal('val_dolares', 18, 2)->default(0);
            $table->string('no_guia_embarque', 60)->nullable();
            $table->string('id_embarque', 60)->nullable();
            $table->timestamps();
        });

        // ── Identificadores del pedimento ─────────────────────
        Schema::create('identificadores_pedimento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->string('clave', 5);
            $table->string('complemento1', 100)->nullable();
            $table->string('complemento2', 100)->nullable();
            $table->string('complemento3', 100)->nullable();
            $table->timestamps();
        });

        // ── Tasas a nivel pedimento ───────────────────────────
        Schema::create('tasas_pedimento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->integer('contribucion');
            $table->string('nombre_contribucion', 20)->nullable();
            $table->integer('cve_tipo_tasa');
            $table->decimal('tasa', 12, 5)->default(0);
            $table->timestamps();
        });

        // ── Cuadro de liquidación ─────────────────────────────
        Schema::create('cuadro_liquidacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->string('concepto_izq', 20)->nullable();
            $table->integer('fp_izq')->default(0);
            $table->decimal('importe_izq', 18, 2)->default(0);
            $table->string('concepto_der', 20)->nullable();
            $table->integer('fp_der')->default(0);
            $table->decimal('importe_der', 18, 2)->default(0);
            $table->decimal('efectivo', 18, 2)->default(0);
            $table->decimal('otros', 18, 2)->default(0);
            $table->decimal('total', 18, 2)->default(0);
            $table->timestamps();
        });

        // ── Pago electrónico ──────────────────────────────────
        Schema::create('pago_electronico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->string('patente', 10)->nullable();
            $table->string('aduana', 10)->nullable();
            $table->string('nombre_institucion', 100)->nullable();
            $table->string('linea_captura', 50)->nullable();
            $table->decimal('importe_pagado', 18, 2)->default(0);
            $table->date('fecha_pago')->nullable();
            $table->string('num_operacion_bancaria', 50)->nullable();
            $table->string('num_transaccion_sat', 50)->nullable();
            $table->string('medio_presentacion', 100)->nullable();
            $table->string('medio_recepcion_cobro', 100)->nullable();
            $table->timestamps();
        });

        // ── Agentes aduanales ─────────────────────────────────
        Schema::create('agentes_aduanales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedimento_id')->constrained('pedimentos')->onDelete('cascade');
            $table->string('nombre', 200)->nullable();
            $table->string('razon_social', 200)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->string('curp', 18)->nullable();
            $table->string('patente', 10)->nullable();
            $table->string('num_serie_certificado', 50)->nullable();
            $table->text('efirma')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agentes_aduanales');
        Schema::dropIfExists('pago_electronico');
        Schema::dropIfExists('cuadro_liquidacion');
        Schema::dropIfExists('tasas_pedimento');
        Schema::dropIfExists('identificadores_pedimento');
        Schema::dropIfExists('facturas');
        Schema::dropIfExists('proveedores_compradores');
    }
};