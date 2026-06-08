<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');

            // Datos generales
            $table->string('num_pedimento', 20)->unique();
            $table->string('referencia', 30)->nullable();
            $table->enum('tipo_operacion', ['IMP', 'EXP']);
            $table->string('cve_pedimento', 5);
            $table->string('regimen', 10);
            $table->string('destino_origen', 2);
            $table->decimal('tipo_cambio', 12, 5);
            $table->decimal('peso_bruto', 14, 3);
            $table->string('aduana_entrada_salida', 5);

            // Transportes
            $table->string('transporte_entrada_salida', 5)->nullable();
            $table->string('transporte_arribo', 5)->nullable();
            $table->string('transporte_salida', 5)->nullable();

            // Importador
            $table->string('rfc_importador', 13);
            $table->string('curp_importador', 18)->nullable();
            $table->string('nombre_importador', 200);
            $table->string('domicilio_importador', 400);

            // Valores
            $table->decimal('valor_dolares', 18, 2)->default(0);
            $table->decimal('valor_aduana', 18, 2)->default(0);
            $table->decimal('precio_pagado_valor_comercial', 18, 2)->default(0);

            // Incrementables
            $table->decimal('val_seguros', 18, 2)->default(0);
            $table->decimal('seguros', 18, 2)->default(0);
            $table->decimal('fletes', 18, 2)->default(0);
            $table->decimal('embalajes', 18, 2)->default(0);
            $table->decimal('otros_incrementables', 18, 2)->default(0);

            // Decrementables
            $table->decimal('transporte_decrementables', 18, 2)->default(0);
            $table->decimal('seguro_decrementables', 18, 2)->default(0);
            $table->decimal('carga_decrementables', 18, 2)->default(0);
            $table->decimal('descarga_decrementables', 18, 2)->default(0);
            $table->decimal('otros_decrementables', 18, 2)->default(0);

            // Fechas y control
            $table->date('fecha_entrada')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('codigo_aceptacion', 50)->nullable();
            $table->string('clave_seccion_aduanera', 10)->nullable();
            $table->string('nombre_aduana_despacho', 200)->nullable();
            $table->string('marcas_numeros_bultos', 200)->nullable();
            $table->integer('total_bultos')->default(0);
            $table->text('observaciones')->nullable();

            $table->enum('estado', ['borrador', 'transmitido', 'pagado', 'liberado'])
                ->default('borrador');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedimentos');
    }
};