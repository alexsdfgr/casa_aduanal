{{-- resources/views/usuarios/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0" style="color:#1a365d">
        <i class="bi bi-people me-1"></i> Usuarios del Sistema
    </h5>
    @if(in_array(auth()->user()->rol, ['ADMIN', 'PROFESOR']))
    <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-success px-3">
        <i class="bi bi-person-plus me-1"></i>
        @if(auth()->user()->rol === 'ADMIN')
            Nuevo Profesor
        @else
            Nuevo Alumno
        @endif
    </a>
    @endif
</div>

<div class="card-aduanal">
    <div class="table-responsive">
        <table class="table table-hover table-aduanal mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Grupo</th>
                    <th>Estado</th>
                    <th>Pedimentos</th>
                    <th>Registrado</th>
                    @if(in_array(auth()->user()->rol, ['ADMIN', 'PROFESOR']))
                    <th class="text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $u)
                <tr>
                    <td class="fw-bold">{{ $u->nombre }}</td>
                    <td><code>{{ $u->username }}</code></td>
                    <td>
                        <span class="rol-badge rol-{{ strtolower($u->rol) }}">{{ $u->rol }}</span>
                    </td>
                    <td>
                        @if($u->rol === 'ALUMNO')
                            <span class="badge bg-info text-dark">{{ $u->grupo ?? 'Sin grupo' }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $u->activo ? 'bg-success' : 'bg-secondary' }}">
                            {{ $u->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>{{ $u->pedimentos->count() }}</td>
                    <td>{{ $u->created_at->format('d/m/Y') }}</td>
                    @if(auth()->user()->rol === 'ADMIN' || (auth()->user()->rol === 'PROFESOR' && $u->profesor_id === auth()->id()))
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('usuarios.edit', $u) }}"
                               class="btn btn-outline-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}"
                                  onsubmit="return confirm('¿Eliminar usuario {{ $u->username }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())
        <div class="p-2">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection
