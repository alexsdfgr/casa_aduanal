<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\Usuario;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        // Solo administradores
        Gate::define('admin', fn(Usuario $u) => $u->rol === 'ADMIN');

        // Administradores y profesores pueden ver todo
        Gate::define('revisar', fn(Usuario $u) => in_array($u->rol, ['ADMIN', 'PROFESOR']));

        // Administradores y alumnos pueden capturar pedimentos
        Gate::define('capturar', fn(Usuario $u) => in_array($u->rol, ['ADMIN', 'ALUMNO']));
    }
}
