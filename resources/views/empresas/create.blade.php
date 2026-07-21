{{-- resources/views/empresas/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Alta de Empresa')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-building-add me-2"></i>Alta y Registro de Empresa
        </h2>
        <p class="text-muted mb-0">Ingresa la información requerida para registrar una empresa en el sistema</p>
    </div>
    <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver a Lista
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-aduanal">
            <div class="card-header-adu">
                <i class="bi bi-file-earmark-text-fill"></i> Formulario de Registro de Empresa
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('empresas.store') }}">
                    @csrf

                    <div class="row g-3">
                        {{-- Nombre de la Empresa --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre de la Empresa <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                       value="{{ old('nombre') }}" placeholder="Ej. Logística Global S.A." required>
                            </div>
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Razón Social --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Razón Social <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                <input type="text" name="razon_social" class="form-control @error('razon_social') is-invalid @enderror"
                                       value="{{ old('razon_social') }}" placeholder="Ej. Logística Global S.A. de C.V." required>
                            </div>
                            @error('razon_social')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ID Fiscal --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ID Fiscal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" name="id_fiscal" class="form-control @error('id_fiscal') is-invalid @enderror"
                                       value="{{ old('id_fiscal') }}" placeholder="Ej. GB880391804 (Opcional)">
                            </div>
                            @error('id_fiscal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- RFC --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">RFC</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                                <input type="text" name="rfc" class="form-control @error('rfc') is-invalid @enderror"
                                       value="{{ old('rfc') }}" placeholder="Ej. LGL120518XXX">
                            </div>
                            @error('rfc')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Comprador o Vendedor --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Comprador o Vendedor <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-arrow-left-right"></i></span>
                                <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="">— Seleccionar —</option>
                                    <option value="COMPRADOR" {{ old('tipo') === 'COMPRADOR' ? 'selected' : '' }}>Comprador</option>
                                    <option value="VENDEDOR" {{ old('tipo') === 'VENDEDOR' ? 'selected' : '' }}>Vendedor</option>
                                    <option value="COMPRADOR/VENDEDOR" {{ old('tipo') === 'COMPRADOR/VENDEDOR' ? 'selected' : '' }}>Ambos (Comprador / Vendedor)</option>
                                </select>
                            </div>
                            @error('tipo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- País --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">País <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                <input type="text" name="pais" class="form-control @error('pais') is-invalid @enderror"
                                       value="{{ old('pais', 'MÉXICO') }}" placeholder="Ej. MÉXICO, ESTADOS UNIDOS" required>
                            </div>
                            @error('pais')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Domicilio --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Domicilio <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="domicilio" class="form-control @error('domicilio') is-invalid @enderror"
                                       value="{{ old('domicilio') }}" placeholder="Calle, número, colonia, CP, municipio/estado" required>
                            </div>
                            @error('domicilio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle me-1"></i> Registrar Empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
