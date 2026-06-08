<?php
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model implements AuthenticatableContract
{
    use Authenticatable, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'username',
        'password',
        'rol',     // ADMIN | PROFESOR | ALUMNO
        'activo',
        'profesor_id',
        'grupo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // ── Helpers de rol ───────────────────────────────────
    public function esAdmin(): bool
    {
        return $this->rol === 'ADMIN';
    }
    public function esProfesor(): bool
    {
        return $this->rol === 'PROFESOR';
    }
    public function esAlumno(): bool
    {
        return $this->rol === 'ALUMNO';
    }

    // ── Relación ─────────────────────────────────────────
    public function pedimentos()
    {
        return $this->hasMany(Pedimento::class, 'usuario_id');
    }

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'profesor_id');
    }

    public function alumnos()
    {
        return $this->hasMany(Usuario::class, 'profesor_id');
    }

    // ── Laravel Auth: campo de autenticación ─────────────
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }
    public function getAuthPassword(): string
    {
        return $this->password;
    }
}
