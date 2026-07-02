{{-- resources/views/grupos/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Grupos')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color:#1a365d">
        <i class="bi bi-collection me-1"></i> Gestión de Grupos
    </h5>
</div>

<div class="row">
    {{-- Tabla de Grupos --}}
    <div class="col-md-8 mb-3">
        <div class="card-aduanal">
            <div class="table-responsive">
                <table class="table table-hover table-aduanal mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Grupo</th>
                            <th>Profesor a Cargo</th>
                            <th>Fecha de Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grupos as $g)
                        <tr>
                            <td>{{ $g->id }}</td>
                            <td class="fw-bold">{{ $g->nombre }}</td>
                            <td>
                                @if($g->profesor)
                                    <span class="badge bg-secondary" style="font-size: .8rem; color: #fff;">{{ $g->profesor->nombre }}</span>
                                @else
                                    <span class="text-muted" style="font-size: .8rem; font-style: italic;">Sin asignar</span>
                                @endif
                            </td>
                            <td>{{ $g->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('grupos.destroy', $g) }}"
                                      onsubmit="return confirm('¿Eliminar el grupo {{ $g->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay grupos registrados todavía.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($grupos->hasPages())
                <div class="p-2 border-top bg-light">{{ $grupos->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Formulario para Crear Grupo --}}
    <div class="col-md-4">
        <div class="card-aduanal">
            <div class="card-header-adu">
                <i class="bi bi-plus-circle-fill"></i> Crear Nuevo Grupo
            </div>
            <div class="p-4" style="background:#fff">
                <form method="POST" action="{{ route('grupos.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Grupo *</label>
                        <input type="text" name="nombre" class="form-control"
                               value="{{ old('nombre') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Profesor a Cargo</label>
                        <select name="profesor_id" class="form-select">
                            <option value="">— Sin profesor —</option>
                            @foreach($profesores as $p)
                                <option value="{{ $p->id }}" {{ old('profesor_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }} ({{ $p->username }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-save me-1"></i> Guardar Grupo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
