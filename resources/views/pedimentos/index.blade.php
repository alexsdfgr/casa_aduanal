{{-- resources/views/pedimentos/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pedimentos')
@section('content')

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0" style="color:#1a365d">
            <i class="bi bi-file-earmark-text me-1"></i>
            {{ auth()->user()->rol === 'ALUMNO' ? 'Mis Pedimentos' : 'Gestión de Pedimentos' }}
        </h5>
        @if(auth()->user()->rol !== 'PROFESOR')
            <a href="{{ route('pedimentos.create') }}" class="btn btn-sm btn-success px-3">
                <i class="bi bi-plus-lg me-1"></i> Nuevo Pedimento
            </a>
        @endif
    </div>

    {{-- Filtros --}}
    <div class="card-aduanal mb-3">
        <div class="p-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold" style="font-size:.8rem">Buscar</label>
                    <input type="text" name="busqueda" class="form-control" value="{{ request('busqueda') }}"
                        placeholder="Núm. pedimento, RFC o importador">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="font-size:.8rem">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">— Todos —</option>
                        <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="transmitido" {{ request('estado') === 'transmitido' ? 'selected' : '' }}>Enviado</option>
                        <option value="pagado" {{ request('estado') === 'pagado' ? 'selected' : '' }}>Pagado</option>
                        <option value="liberado" {{ request('estado') === 'liberado' ? 'selected' : '' }}>Liberado</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('pedimentos.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-lg"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card-aduanal">
        <div class="table-responsive">
            <table class="table table-hover table-aduanal mb-0">
                <thead>
                    <tr>
                        <th>Núm. Pedimento</th>
                        <th>Ref.</th>
                        <th>Oper.</th>
                        <th>Régimen</th>
                        <th>RFC Importador</th>
                        <th>Importador / Exportador</th>
                        <th>F. Entrada</th>
                        <th class="text-end">Total $MXN</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedimentos as $p)
                        <tr>
                            <td>
                                <span style="font-family:monospace;font-weight:700;color:#1a365d">
                                    {{ $p->num_pedimento }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $p->referencia ?? '—' }}</small></td>
                            <td>
                                <span
                                    class="badge {{ $p->tipo_operacion === 'IMP' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                                    {{ $p->tipo_operacion }}
                                </span>
                            </td>
                            <td><code style="font-size:.72rem">{{ $p->regimen }}</code></td>
                            <td><code style="font-size:.72rem">{{ $p->rfc_importador }}</code></td>
                            <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                                title="{{ $p->nombre_importador }}">
                                {{ $p->nombre_importador }}
                            </td>
                            <td>{{ $p->fecha_entrada?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-end fw-bold">
                                @if($p->cuadroLiquidacion)
                                    ${{ number_format($p->cuadroLiquidacion->total, 2) }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $label = match ($p->estado) {
                                        'borrador' => ['secondary', 'Borrador'],
                                        'transmitido' => ['info', 'Enviado'],
                                        'pagado' => ['warning', 'Pagado'],
                                        'liberado' => ['success', 'Liberado'],
                                        default => ['secondary', $p->estado],
                                    };
                                @endphp
                                <span class="badge bg-{{ $label[0] }}">{{ $label[1] }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('pedimentos.show', $p) }}" class="btn btn-outline-success" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array(auth()->user()->rol, ['PROFESOR', 'ADMIN']) && $p->estado !== 'liberado')
                                        <form method="POST" action="{{ route('pedimentos.liberar', $p) }}"
                                              onsubmit="return confirm('¿Marcar el pedimento {{ $p->num_pedimento }} como liberado?')">
                                            @csrf
                                            <button class="btn btn-outline-primary" title="Marcar como Liberado">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->rol !== 'PROFESOR')
                                        <a href="{{ route('pedimentos.edit', $p) }}" class="btn btn-outline-secondary"
                                            title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('pedimentos.destroy', $p) }}"
                                            onsubmit="return confirm('¿Eliminar pedimento {{ $p->num_pedimento }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No hay pedimentos registrados.
                                @if(auth()->user()->rol !== 'PROFESOR')
                                    <a href="{{ route('pedimentos.create') }}">Crear el primero</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pedimentos->hasPages())
            <div class="p-3">{{ $pedimentos->links() }}</div>
        @endif
    </div>

@endsection