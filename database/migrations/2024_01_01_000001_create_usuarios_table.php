<?php
// database/migrations/2024_01_01_000001_create_usuarios_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('username', 60)->unique();
            $table->string('password');
            // Roles: ADMIN, PROFESOR, ALUMNO
            $table->enum('rol', ['ADMIN', 'PROFESOR', 'ALUMNO'])->default('ALUMNO');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
