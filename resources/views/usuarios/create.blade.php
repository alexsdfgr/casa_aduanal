{{-- resources/views/usuarios/create.blade.php --}}
@extends('layouts.app')
@section('title', isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario')
@section('content')

<div class="row justify-content-center">
<div class="col-md-6">
<div class="card-aduanal">
    <div class="card-header-adu">
        <i class="bi bi-person-gear"></i>
        {{ isset($usuario) ? 'Editar: '.$usuario->username : 'Nuevo Usuario' }}
    </div>
    <div class="p-4" style="background:#fff">
        <form method="POST"
              action="{{ isset($usuario) ? route('usuarios.update',$usuario) : route('usuarios.store') }}">
            @csrf
            @if(isset($usuario)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Completo *</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ old('nombre', $usuario->nombre ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nombre de Usuario *</label>
                <input type="text" name="username" class="form-control field-code"
                       value="{{ old('username', $usuario->username ?? '') }}"
                       required autocomplete="username">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Contraseña
                    @if(isset($usuario))
                        <small class="text-muted fw-normal">(dejar vacío para no cambiar)</small>
                    @else * @endif
                </label>
                <div style="position:relative;">
                    <input type="password" id="reg_password" name="password" class="form-control"
                           autocomplete="new-password" style="padding-right: 45px;"
                           @if(!isset($usuario)) required @endif>
                    <button type="button" onclick="togglePasswordVisibility('reg_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #718096; font-size: 1.1rem; padding: 0;">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Confirmar Contraseña</label>
                <div style="position:relative;">
                    <input type="password" id="reg_password_confirmation" name="password_confirmation" class="form-control"
                           autocomplete="new-password" style="padding-right: 45px;">
                    <button type="button" onclick="togglePasswordVisibility('reg_password_confirmation', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #718096; font-size: 1.1rem; padding: 0;">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Grupo {!! auth()->user()->rol === 'PROFESOR' ? '<span class="text-danger">*</span>' : '<small class="text-muted fw-normal">(opcional)</small>' !!}</label>
                <input type="text" name="grupo" class="form-control"
                       value="{{ old('grupo', $usuario->grupo ?? '') }}"
                       placeholder="Ej. 3A, Vespertino, etc."
                       {{ auth()->user()->rol === 'PROFESOR' ? 'required' : '' }}>
            </div>

            @if(auth()->user()->rol === 'ADMIN')
            <div class="mb-3">
                <label class="form-label fw-bold">Rol *</label>
                <select name="rol" class="form-select" required>
                    <option value="ALUMNO"   {{ old('rol',$usuario->rol??'ALUMNO')==='ALUMNO'  ?'selected':'' }}>
                        ALUMNO – Registra sus propios pedimentos
                    </option>
                    <option value="PROFESOR" {{ old('rol',$usuario->rol??'')==='PROFESOR'?'selected':'' }}>
                        PROFESOR – Revisa pedimentos de alumnos
                    </option>
                    <option value="ADMIN"    {{ old('rol',$usuario->rol??'')==='ADMIN'   ?'selected':'' }}>
                        ADMIN – Acceso total al sistema
                    </option>
                </select>
            </div>
            @endif

            @if(isset($usuario))
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="activo" value="1" id="chkActivo"
                           {{ old('activo', $usuario->activo) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="chkActivo">Usuario Activo</label>
                </div>
            </div>
            @endif

            <div class="d-flex justify-content-between">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i>
                    {{ isset($usuario) ? 'Guardar Cambios' : 'Crear Usuario' }}
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>

@endsection
