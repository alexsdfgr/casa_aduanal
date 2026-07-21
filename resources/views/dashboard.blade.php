{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

@php $rol = auth()->user()->rol; $nombre = auth()->user()->nombre; @endphp

<div class="dashboard-header mb-4">
    <h2><i class="bi bi-person-circle me-2"></i>Bienvenido, {{ $nombre }}</h2>
    <p>Panel de Control · <span class="rol-badge rol-{{ strtolower($rol) }}">{{ $rol }}</span> · Simulador Aduanal UPTex · Anexo 22 RGCE 2024</p>
</div>

@if($rol === 'ADMIN')

{{-- KPIs (2 columnas) --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Total Pedimentos','value'=>$stats['total'],    'icon'=>'bi-file-earmark-text','color'=>'#00843D'],
        ['label'=>'Borrador',        'value'=>$stats['borrador'], 'icon'=>'bi-pencil-square',    'color'=>'#718096'],
    ] as $k)
    <div class="col-sm-6">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi {{ $k['icon'] }}" style="color:{{ $k['color'] }}"></i></div>
            <div>
                <div class="kpi-label">{{ $k['label'] }}</div>
                <div class="kpi-value" style="color:{{ $k['color'] }}">{{ $k['value'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Opciones de Gestión: Estiradas al mismo ancho que los KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <a href="{{ route('pedimentos.index') }}" class="card-option w-100 m-0" style="display: flex; align-items: center; justify-content: center; gap: 10px; height: 100%;">
            <i class="bi bi-file-earmark-text"></i>
            Gestión de Pedimentos
        </a>
    </div>
    <div class="col-sm-4">
        <a href="{{ route('usuarios.index') }}" class="card-option w-100 m-0" style="display: flex; align-items: center; justify-content: center; gap: 10px; height: 100%;">
            <i class="bi bi-people"></i>
            Gestión de Profesores
        </a>
    </div>
    <div class="col-sm-4">
        <a href="{{ route('grupos.index') }}" class="card-option w-100 m-0" style="display: flex; align-items: center; justify-content: center; gap: 10px; height: 100%;">
            <i class="bi bi-collection"></i>
            Gestión de Grupos
        </a>
    </div>
</div>

@elseif($rol === 'PROFESOR')

<div class="grid-options mb-4">
    <a href="{{ route('pedimentos.index') }}" class="card-option">
        <i class="bi bi-search"></i>Revisar Pedimentos
    </a>
    <a href="{{ route('usuarios.index') }}" class="card-option">
        <i class="bi bi-mortarboard"></i>Ver Alumnos
    </a>
    <a href="{{ route('pedimentos.index') }}?estado=transmitido" class="card-option">
        <i class="bi bi-hourglass-split"></i>Pendientes de Revisión
        @if($stats['transmitido'] > 0)
            <span class="badge bg-danger rounded-pill">{{ $stats['transmitido'] }}</span>
        @endif
    </a>
</div>

@elseif($rol === 'ALUMNO')

<div class="grid-options mb-4">
    <a href="{{ route('pedimentos.index') }}" class="card-option">
        <i class="bi bi-file-earmark-text"></i>Mis Pedimentos
    </a>
    <a href="{{ route('pedimentos.index') }}?estado=liberado" class="card-option">
        <i class="bi bi-check-circle"></i>Pedimentos Aprobados
    </a>
    <a href="{{ route('pedimentos.create') }}" class="card-option">
        <i class="bi bi-plus-circle"></i>Registrar Pedimento
    </a>
    <a href="{{ route('empresas.create') }}" class="card-option">
        <i class="bi bi-building-add"></i>Registrar Empresa
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Mis Pedimentos','value'=>$stats['total'],                          'icon'=>'bi-file-earmark-text'],
        ['label'=>'En Proceso',    'value'=>$stats['borrador']+$stats['transmitido'], 'icon'=>'bi-hourglass-split'],
        ['label'=>'Aprobados',     'value'=>$stats['liberado'],                       'icon'=>'bi-check-circle'],
    ] as $k)
    <div class="col-sm-4">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi {{ $k['icon'] }}"></i></div>
            <div>
                <div class="kpi-label">{{ $k['label'] }}</div>
                <div class="kpi-value">{{ $k['value'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endif

{{-- Tabla recientes --}}
<div class="card-aduanal">
    <div class="card-header-adu">
        <i class="bi bi-clock-history"></i>
        {{ $rol === 'ALUMNO' ? 'Mis Pedimentos Recientes' : 'Pedimentos Recientes' }}
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-aduanal mb-0">
            <thead>
                <tr>
                    <th>Núm. Pedimento</th><th>Importador</th><th>Régimen</th>
                    <th>F. Entrada</th><th class="text-end">Total $MXN</th>
                    <th>Estado</th><th class="text-center">Ver</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recientes as $p)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700">{{ $p->num_pedimento }}</span></td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p->nombre_importador }}</td>
                    <td><code style="font-size:.72rem">{{ $p->regimen }}</code></td>
                    <td>{{ $p->fecha_entrada?->format('d/m/Y') ?? '—' }}</td>
                    <td class="text-end fw-bold">
                        @if($p->cuadroLiquidacion) ${{ number_format($p->cuadroLiquidacion->total,2) }}
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td>
                        @php $label=match($p->estado){
                            'borrador'    =>['secondary','Borrador'],
                            'transmitido' =>['info','Enviado'],
                            'pagado'      =>['warning','Pagado'],
                            'liberado'    =>['success','Liberado'],
                            default       =>['secondary',$p->estado]
                        }; @endphp
                        <span class="badge bg-{{ $label[0] }}">{{ $label[1] }}</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('pedimentos.show',$p) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay pedimentos todavía.
                    @if($rol !== 'PROFESOR') <a href="{{ route('pedimentos.create') }}">Crear el primero</a> @endif
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-2 text-end border-top" style="background:#fff">
        <a href="{{ route('pedimentos.index') }}" class="btn btn-sm btn-outline-success">
            Ver todos <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>

@endsection