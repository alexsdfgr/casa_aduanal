<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('cascade');
            $table->string('nombre', 200);
            $table->string('razon_social', 200);
            $table->string('id_fiscal', 50)->nullable();
            $table->string('rfc', 20)->nullable();
            $table->string('domicilio', 400)->nullable();
            $table->string('pais', 100)->default('MÉXICO');
            $table->enum('tipo', ['COMPRADOR', 'VENDEDOR', 'COMPRADOR/VENDEDOR'])->default('COMPRADOR');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
