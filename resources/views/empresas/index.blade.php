{{-- resources/views/empresas/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Empresas Registradas')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--text-color, #1a365d);">
            <i class="bi bi-building me-2"></i>Empresas Registradas
        </h2>
        <p class="text-muted mb-0">Gestión y catálogo de empresas compradoras y vendedoras</p>
    </div>
    <a href="{{ route('empresas.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-1"></i> Registrar Nueva Empresa
    </a>
</div>

<div class="card-aduanal">
    <div class="card-header-adu d-flex align-items-center justify-content-between">
        <span><i class="bi bi-building-gear me-2"></i> Listado de Empresas</span>
        <span class="badge bg-secondary">{{ $empresas->total() }} registradas</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-aduanal mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Razón Social</th>
                    <th>ID Fiscal / RFC</th>
                    <th>Tipo</th>
                    <th>País</th>
                    <th>Domicilio</th>
                    @if(auth()->user()->rol !== 'ALUMNO')
                        <th>Registrado Por</th>
                    @endif
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empresas as $empresa)
                <tr>
                    <td class="fw-bold text-primary-emphasis">
                        <i class="bi bi-building me-1 text-muted"></i>{{ $empresa->nombre }}
                    </td>
                    <td>{{ $empresa->razon_social }}</td>
                    <td>
                        @if($empresa->id_fiscal)
                            <div><span class="badge bg-light text-dark border">ID Fiscal: {{ $empresa->id_fiscal }}</span></div>
                        @endif
                        @if($empresa->rfc)
                            <div><code style="font-size: .8rem;">RFC: {{ $empresa->rfc }}</code></div>
                        @endif
                        @if(!$empresa->id_fiscal && !$empresa->rfc)
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeColor = match($empresa->tipo) {
                                'COMPRADOR' => 'info',
                                'VENDEDOR'  => 'warning',
                                default     => 'primary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }}">{{ $empresa->tipo }}</span>
                    </td>
                    <td>{{ $empresa->pais }}</td>
                    <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $empresa->domicilio }}">
                        {{ $empresa->domicilio ?? '—' }}
                    </td>
                    @if(auth()->user()->rol !== 'ALUMNO')
                        <td>
                            @if($empresa->usuario)
                                <small class="text-muted">{{ $empresa->usuario->nombre }} ({{ $empresa->usuario->rol }})</small>
                            @else
                                <small class="text-muted">Sistema</small>
                            @endif
                        </td>
                    @endif
                    <td class="text-center">
                        <form method="POST" action="{{ route('empresas.destroy', $empresa) }}"
                              onsubmit="return confirm('¿Estás seguro de eliminar la empresa {{ $empresa->nombre }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar empresa">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->rol !== 'ALUMNO' ? 8 : 7 }}" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay empresas registradas todavía.
                        <div class="mt-2">
                            <a href="{{ route('empresas.create') }}" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i>Registrar la primera empresa
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($empresas->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $empresas->links() }}
        </div>
    @endif
</div>

@endsection
