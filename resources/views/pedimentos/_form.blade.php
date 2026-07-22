
@php
    $p = $pedimento ?? null;
    $pv = $p?->proveedores->first() ?? null;
    $fv = $p?->facturas->first() ?? null;
    $liq = $p?->cuadroLiquidacion ?? null;
    $pag = $p?->pagoElectronico ?? null;
    $ag = $p?->agente ?? null;
    $ids = $p ? $p->identificadores->toArray() : [];
    $ts = $p ? $p->tasas->toArray() : [];
@endphp

<style>
    :root {
        --adu-dark:        #1e293b;
        --adu-blue:        #38bdf8;
        --adu-gold:        #fde047;
        --adu-sub-bg:      rgba(31, 41, 55, 0.95);
        --adu-row-alt:     rgba(30, 41, 59, 0.5);
        --adu-label-color: #cbd5e1;
        --adu-label-size:  .68rem;
        --adu-cell-size:   .78rem;
    }

    /* ── Etiquetas de campo ─────────────────────────────────────── */
    .lbl {
        font-size: var(--adu-label-size);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--adu-label-color);
    }

    /* ── Sub-encabezado azul (igual que show) ───────────────────── */
    .sec-header {
        background: var(--adu-sub-bg);
        padding: .32rem .75rem;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--adu-blue);
        border-top: 1px solid #c8d8ed;
        border-bottom: 1px solid #c8d8ed;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sec-header .badge-apendice {
        font-size: .6rem;
        font-weight: 600;
        color: #64748b;
        background: #dde8f4;
        padding: .1rem .45rem;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    /* ── Encabezado principal de tarjeta ────────────────────────── */
    .card-adu-header {
        background: var(--adu-dark);
        color: #fff;
        padding: .42rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .card-adu-header .ref  { font-size: .76rem; font-weight: 400; }
    .card-adu-header .gold { color: var(--adu-gold); font-size: .72rem; }

    /* ── Monospace ──────────────────────────────────────────────── */
    .fc { font-family: 'Courier New', monospace; }

    /* ── Cuerpo de sección (padding interno) ────────────────────── */
    .sec-body { padding: .75rem 1rem; }

    /* ── Línea de captura ───────────────────────────────────────── */
    .linea-captura-display {
        font-family: 'Courier New', monospace;
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .12em;
        color: var(--adu-dark);
        background: #f0f4f8;
        border: 1px dashed #94a3b8;
        border-radius: 4px;
        padding: .45rem .9rem;
        display: inline-block;
    }

    /* ── Total liquidación ──────────────────────────────────────── */
    .total-input {
        background: var(--adu-dark) !important;
        color: var(--adu-gold) !important;
        border-color: var(--adu-dark) !important;
        font-family: 'Courier New', monospace;
        font-weight: 700;
    }

    /* ── form-label compacto ────────────────────────────────────── */
    .form-label {
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--adu-label-color);
        margin-bottom: .2rem;
    }
    .form-control, .form-select {
        font-size: .82rem;
    }
    .form-text {
        font-size: .65rem;
        color: #94a3b8;
    }

    /* ── Campo calculado automáticamente ───────────────────────── */
    .campo-auto {
        background: #f0f4f8 !important;
        color: #334155 !important;
        border: 1px dashed #94a3b8 !important;
        font-family: 'Courier New', monospace;
        font-weight: 600;
        cursor: not-allowed;
    }
    .campo-auto:focus {
        box-shadow: none !important;
        border-color: #94a3b8 !important;
    }
    .label-auto {
        color: #0369a1 !important;
    }
    .label-auto::after {
        content: ' ⚙';
        font-size: .6rem;
        color: #0ea5e9;
        font-style: normal;
    }
    .badge-auto {
        font-size: .58rem;
        font-weight: 600;
        color: #0369a1;
        background: #e0f2fe;
        padding: .08rem .35rem;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: .03em;
        display: inline-block;
        margin-left: .3rem;
        vertical-align: middle;
    }

    /* ── Placeholder visual en selects ─────────────────────────── */
    select.form-select,
    select.form-select-sm {
        color: #1e293b;
    }
    select.form-select option[value=""],
    select.form-select-sm option[value=""] {
        display: none;
    }
    select.form-select:required:invalid,
    select.form-select-sm:required:invalid {
        color: #94a3b8;
    }
    select.form-select:has(option[value=""]:checked),
    select.form-select-sm:has(option[value=""]:checked) {
        color: #94a3b8;
    }
</style>

<form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if($put ?? false) @method('PUT') @endif

    {{-- ══════════════════════════════════════════════════════════════
         TARJETA ÚNICA — mismo contenedor que el show
         ══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border:2px solid var(--adu-dark);border-radius:4px;overflow:hidden">

        <div class="card-adu-header">
            <strong><i class="bi bi-file-earmark-text me-1"></i> REGISTRAR NUEVO PEDIMENTO</strong>
            <span class="ref">
                Completa los datos del pedimento
                &nbsp;·&nbsp;
                <span style="color:#7dd3fc;font-size:.68rem"><i class="bi bi-gear-fill me-1"></i>⚙ = calculado automáticamente</span>
            </span>
        </div>

        <div class="card-body p-0">

            {{-- ── 1. DATOS GENERALES ──────────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-file-earmark-text me-1"></i> Datos Generales del Pedimento</span>
                <span class="badge-apendice">Cabecera – Anexo 22</span>
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Núm. Pedimento <span class="text-danger">*</span></label>
                        <input type="text" name="pedimento[num_pedimento]"
                               class="form-control fc @error('pedimento.num_pedimento') is-invalid @enderror"
                               value="{{ old('pedimento.num_pedimento', $p?->num_pedimento) }}"
                               maxlength="20" placeholder="AA PP PPPP NNNNNNN" required>
                        <div class="form-text">Año · Aduana · Patente · Folio</div>
                        @error('pedimento.num_pedimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        {{-- Referencia: calculada del Proveedor/Cliente + Ops. Año / Pedimento Año --}}
                        <label class="form-label label-auto">Referencia <span class="badge-auto">Auto</span></label>
                        <input type="text" name="pedimento[referencia]" id="campoReferencia" class="form-control fc campo-auto"
                               value="{{ old('pedimento.referencia', $p?->referencia) }}" maxlength="30" readonly
                               title="Se genera automáticamente: 2 letras Proveedor/Cliente + Ops. Año / Pedimento Año (Ej. IV003/001)">
                        <div class="form-text">Ej: IV003/001</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo Operación <span class="text-danger">*</span></label>
                        <select name="pedimento[tipo_operacion]" class="form-select" required>
                            <option value="" disabled hidden {{ !old('pedimento.tipo_operacion', $p?->tipo_operacion) ? 'selected' : '' }}>— Seleccionar —</option>
                            @foreach(['IMP' => 'IMP – Importación', 'EXP' => 'EXP – Exportación', 'TRA' => 'TRA – Tránsitos'] as $val => $lbl)
                                <option value="{{ $val }}" {{ old('pedimento.tipo_operacion', $p?->tipo_operacion) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Clave Pedimento <span class="text-danger">*</span></label>
                        <select name="pedimento[cve_pedimento]" class="form-select" required>
                            <option value="" disabled hidden {{ !old('pedimento.cve_pedimento', $p?->cve_pedimento) ? 'selected' : '' }}>— Seleccionar —</option>
                            @foreach([
                                    'A1' => 'A1 - IMPORTACION O EXPORTACION DEFINITIVA',
                                    'A3' => 'A3 - REGULARIZACION DE MERCANCIAS',
                                    'A4' => 'A4 - RECTIFICACION DE PEDIMENTOS',
                                    'AD' => 'AD - IMPORTACION DEFINITIVA DE VEHICULOS USADOS',
                                    'AF' => 'AF - IMPORTACION Y EXPORTACION TEMPORAL DE ACTIVOS FIJOS',
                                    'AJ' => 'AJ - IMPORTACION Y EXPORTACION POR CONTENEDORES Y AVIONES',
                                    'BA' => 'BA - IMPORTACION Y EXPORTACION POR EMPRESAS CON REGISTRO EN EL ESQUEMA DE CERTIFICACION',
                                    'BB' => 'BB - EXPORTACION DEFINITIVA DE MERCANCIAS IMPORTADAS TEMPORALMENTE (EMPRESAS IMMEX)',
                                    'BC' => 'BC - IMPORTACION Y EXPORTACION DE MERCANCIAS DE DONACION',
                                    'BD' => 'BD - IMPORTACION Y EXPORTACION PARA REPARACION O MANTENIMIENTO',
                                    'BE' => 'BE - IMPORTACION Y EXPORTACION DE MERCANCIAS PARA TRANSFORMACION EN RECINTO FISCALIZADO',
                                    'BF' => 'BF - EXPORTACION VIRTUAL DE MERCANCIAS (DEVOLUCION)',
                                    'BH' => 'BH - IMPORTACION Y EXPORTACION DE MERCANCIAS DESTINADAS A LA CONSTRUCCION DE VIVIENDAS',
                                    'BI' => 'BI - IMPORTACION Y EXPORTACION POR EMPRESAS CON PROGRAMA ECE',
                                    'BM' => 'BM - IMPORTACION Y EXPORTACION DE MERCANCIAS PARA MANTENIMIENTO Y REPARACION',
                                    'BO' => 'BO - EXPORTACION DEFINITIVA DE MERCANCIAS PARA TRABAJOS DE EXPLORACION',
                                    'BP' => 'BP - IMPORTACION Y EXPORTACION POR EMPRESAS QUE OPERAN BAJO EL REGIMEN DE RECINTO FISCALIZADO ESTRATEGICO',
                                    'BR' => 'BR - EXPORTACION DE MERCANCIAS EN SUSTITUCION',
                                    'C1' => 'C1 - IMPORTACION TEMPORAL DE MERCANCIAS PARA SER RETORNADAS EN EL MISMO ESTADO',
                                    'C2' => 'C2 - IMPORTACION TEMPORAL DE MERCANCIAS PARA PROCESOS DE TRANSFORMACION O REPARACION',
                                    'C3' => 'C3 - IMPORTACION TEMPORAL DE VEHICULOS DE PRUEBA',
                                    'CP' => 'CP - IMPORTACION TEMPORAL DE CAJAS DE TRAILER Y CONTENEDORES',
                                    'D1' => 'D1 - RETORNO DE MERCANCIAS POR DESISTIMIENTO O SUSTITUCION',
                                    'E1' => 'E1 - EXPORTACION TEMPORAL PARA RETORNO EN EL MISMO ESTADO',
                                    'E2' => 'E2 - EXPORTACION TEMPORAL DE MERCANCIAS PARA REPARACION O TRANSFORMACION',
                                    'E3' => 'E3 - RETORNO AL PAIS DE MERCANCIAS EXPORTADAS TEMPORALMENTE',
                                    'E4' => 'E4 - RETORNO AL PAIS DE MERCANCIAS EXPORTADAS TEMPORALMENTE PARA REPARACION',
                                    'F2' => 'F2 - IMPORTACION DEFINITIVA DE MERCANCIAS DESTINADAS A MERCADO NACIONAL',
                                    'F3' => 'F3 - EXPORTACION DEFINITIVA DE MERCANCIAS DE MERCADO NACIONAL',
                                    'F4' => 'F4 - CAMBIO DE REGIMEN DE IMPORTACION TEMPORAL A DEFINITIVA',
                                    'F5' => 'F5 - CAMBIO DE REGIMEN DE MERCANCIAS IMPORTADAS TEMPORALMENTE (VIRTUALES)',
                                    'F8' => 'F8 - REIMPORTACION DE MERCANCIAS EXPORTADAS DEFINITIVAMENTE',
                                    'F9' => 'F9 - IMPORTACION DEFINITIVA DE MERCANCIAS EN FRANJA O REGION FRONTERIZA',
                                    'G1' => 'G1 - REIMPORTACION EN EL MISMO ESTADO',
                                    'G2' => 'G2 - REIMPORTACION POR TRANSFORMACION O REPARACION',
                                    'G6' => 'G6 - IMPORTACION DEFINITIVA DE MERCANCIAS POR EMPRESAS CON PROGRAMA IMMEX',
                                    'G7' => 'G7 - EXPORTACION DEFINITIVA DE MERCANCIAS POR EMPRESAS CON PROGRAMA IMMEX',
                                    'H1' => 'H1 - RETORNO DE MERCANCIAS EN EL MISMO ESTADO',
                                    'H8' => 'H8 - RETORNO DE MERCANCIAS POR SUSTITUCION',
                                    'I1' => 'I1 - IMPORTACION Y EXPORTACION POR MENSAJERIA Y PAQUETERIA',
                                    'IM' => 'IM - IMPORTACION TEMPORAL DE BIENES PARA EL CUMPLIMIENTO DE CONTRATOS (IMMEX)',
                                    'J1' => 'J1 - RETORNO DE MERCANCIAS EXPORTADAS TEMPORALMENTE (PROCESO)',
                                    'J2' => 'J2 - RETORNO DE MERCANCIAS EXPORTADAS TEMPORALMENTE (MISMO ESTADO)',
                                    'K1' => 'K1 - DESISTIMIENTO DE REGIMEN Y RETORNO DE MERCANCIAS',
                                    'K2' => 'K2 - EXPORTACION DE MERCANCIAS POR DESISTIMIENTO',
                                    'L1' => 'L1 - PEQUENA IMPORTACION DEFINITIVA',
                                    'M1' => 'M1 - INSUMOS Y MERCANCIAS PARA PROCESOS DE TRANSFORMACION (CONSOLIDADO)',
                                    'M2' => 'M2 - EXPORTACION DE INSUMOS Y MERCANCIAS (CONSOLIDADO)',
                                    'M3' => 'M3 - EXPORTACION TEMPORAL DE INSUMOS Y MERCANCIAS',
                                    'M4' => 'M4 - IMPORTACION TEMPORAL DE INSUMOS Y MERCANCIAS',
                                    'M5' => 'M5 - IMPORTACION DEFINITIVA DE INSUMOS Y MERCANCIAS',
                                    'P1' => 'P1 - RE-EXPEDICION DE MERCANCIAS DE FRANJA O REGION FRONTERIZA AL INTERIOR',
                                    'R1' => 'R1 - RECTIFICACION DE PEDIMENTOS',
                                    'S2' => 'S2 - IMPORTACION Y EXPORTACION DE MERCANCIAS PARA SEGURIDAD NACIONAL',
                                    'T1' => 'T1 - TRANSITO INTERNO',
                                    'T3' => 'T3 - TRANSITO INTERNACIONAL POR TERRITORIO NACIONAL',
                                    'T6' => 'T6 - TRANSITO INTERNACIONAL POR TERRITORIO EXTRANJERO',
                                    'T7' => 'T7 - TRANSITO INTERNACIONAL DE MERCANCIAS (CONTENEDORES)',
                                    'T9' => 'T9 - TRANSITO INTERNO PARA IMPORTACION',
                                    'V1' => 'V1 - TRANSFERENCIAS DE MERCANCIAS (IMPORTACION TEMPORAL VIRTUAL)',
                                    'V2' => 'V2 - TRANSFERENCIAS DE MERCANCIAS DE VEHICULOS (VIRTUAL)',
                                    'V5' => 'V5 - RETORNO DE MERCANCIAS (VIRTUAL)',
                                    'V6' => 'V6 - TRANSFERENCIAS DE MERCANCIAS POR EMPRESAS CON PROGRAMA IMMEX',
                                    'V7' => 'V7 - TRANSFERENCIAS DE MERCANCIAS PARA TRANSFORMACION (VIRTUAL)',
                                    'V9' => 'V9 - TRANSFERENCIAS DE MERCANCIAS POR EMPRESAS DE LA INDUSTRIA AUTOMOTRIZ',
                                    'VD' => 'VD - VIRTUALES DE DONACION',
                                    'VE' => 'VE - VIRTUALES DE EXPORTACION',
                                ] as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('pedimento.cve_pedimento', $p?->cve_pedimento) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Apéndice 2</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Régimen <span class="text-danger">*</span></label>
                        <select name="pedimento[regimen]" class="form-select" required>
                            <option value="" disabled hidden {{ !old('pedimento.regimen', $p?->regimen) ? 'selected' : '' }}>— Seleccionar —</option>
                            @foreach([
                                    'IMD' => 'IMD – Definitivo de importación',
                                    'EXD' => 'EXD – Definitivo de exportación',
                                    'ITR' => 'ITR – Temporales de imp. para retornar al extranjero en el mismo estado',
                                    'ITE' => 'ITE – Temporales de imp. para elaboración, transformación o reparación (IMMEX)',
                                    'ETR' => 'ETR – Temporales de exp. para retornar al país en el mismo estado',
                                    'ETE' => 'ETE – Temporales de exp. para elaboración, transformación o reparación',
                                    'DFI' => 'DFI – Depósito fiscal',
                                    'RFE' => 'RFE – Elaboración, transformación o reparación en recinto fiscalizado',
                                    'TRA' => 'TRA – Tránsitos',
                                    'RFS' => 'RFS – Recinto fiscalizado estratégico'
                                ] as $clave => $descripcion)
                                    <option value="{{ $clave }}" {{ old('pedimento.regimen', $p?->regimen) === $clave ? 'selected' : '' }}>{{ $descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Destino/Origen <span class="text-danger">*</span></label>
                        <select name="pedimento[destino_origen]" class="form-select" required>
                            <option value="" disabled hidden {{ !old('pedimento.destino_origen', $p?->destino_origen) ? 'selected' : '' }}>— —</option>
                            <option value="1" {{ old('pedimento.destino_origen', $p?->destino_origen) == 1 ? 'selected' : '' }}>1 – Interior</option>
                            <option value="9" {{ old('pedimento.destino_origen', $p?->destino_origen) == 9 ? 'selected' : '' }}>9 – Extranjero</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo Cambio <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" name="pedimento[tipo_cambio]" id="tipoCambio" class="form-control fc"
                                   value="{{ old('pedimento.tipo_cambio', $p?->tipo_cambio) }}" step="0.00001" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Peso Bruto (kg) <span class="text-danger">*</span></label>
                        <input type="number" name="pedimento[peso_bruto]" class="form-control fc"
                               value="{{ old('pedimento.peso_bruto', $p?->peso_bruto) }}" step="0.001" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Aduana E/S <span class="text-danger">*</span></label>
                        <select name="pedimento[aduana_entrada_salida]" id="aduanaES" class="form-select" required>
                            <option value="" disabled hidden {{ !old('pedimento.aduana_entrada_salida', $p?->aduana_entrada_salida) ? 'selected' : '' }}>— Seleccionar —</option>
                            @foreach(['470' => '470 – Aer. Int. CDMX', '010' => '010 – Tijuana', '020' => '020 – Mexicali', '240' => '240 – Nuevo Laredo', '440' => '440 – Manzanillo', '480' => '480 – Toluca', '530' => '530 – Veracruz', '600' => '600 – Monterrey', '640' => '640 – Guadalajara'] as $v => $l)
                                <option value="{{ $v }}" {{ old('pedimento.aduana_entrada_salida', $p?->aduana_entrada_salida) === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Apéndice 1</div>
                    </div>
                </div>
            </div>

            {{-- ── 2. MEDIOS DE TRANSPORTE ──────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-truck me-1"></i> Medios de Transporte</span>
                <span class="badge-apendice">Apéndice 4</span>
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    @foreach(['transporte_entrada_salida' => 'Entrada / Salida', 'transporte_arribo' => 'Arribo', 'transporte_salida' => 'Salida'] as $field => $label)
                        <div class="col-md-4">
                            <label class="form-label">{{ $label }}</label>
                            <select name="pedimento[{{ $field }}]" class="form-select">
                                <option value="" disabled hidden {{ !old("pedimento.$field", $p?->$field) ? 'selected' : '' }}>— Seleccionar —</option>
                                @foreach(['1' => '1 – Autotransporte', '3' => '3 – Ferroviario', '4' => '4 – Aéreo', '7' => '7 – Marítimo', '12' => '12 – Mensajería', '9' => '9 – Otro'] as $v => $l)
                                    <option value="{{ $v }}" {{ old("pedimento.{$field}", $p?->$field) === $v ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── 3. IMPORTADOR / EXPORTADOR ──────────────────────────────── --}}
            <div class="sec-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-building me-1"></i> Datos del Importador / Exportador (Empresa)</span>
                @if(!empty($empresasRegistradas) && count($empresasRegistradas) > 0)
                    <span class="badge bg-info text-dark font-normal" style="font-size: .75rem; text-transform: none;">
                        <i class="bi bi-building-check me-1"></i> {{ count($empresasRegistradas) }} empresa(s) disponible(s)
                    </span>
                @endif
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    @if(!empty($empresasRegistradas) && count($empresasRegistradas) > 0)
                    <div class="col-12 mb-2">
                        <label class="form-label text-primary fw-bold">
                            <i class="bi bi-building-fill-add me-1"></i> Seleccionar Empresa Registrada para Importador / Exportador
                        </label>
                        <div class="input-group">
                            <select id="selectEmpresaRegistrada" class="form-select border-primary" style="background-color: var(--bg-alt);">
                                <option value="">— Seleccionar de mis empresas registradas —</option>
                                @foreach($empresasRegistradas as $emp)
                                    <option value="{{ $emp->id }}"
                                            data-nombre="{{ $emp->nombre }}"
                                            data-razon="{{ $emp->razon_social }}"
                                            data-rfc="{{ $emp->rfc }}"
                                            data-id-fiscal="{{ $emp->id_fiscal }}"
                                            data-domicilio="{{ $emp->domicilio }}"
                                            data-pais="{{ $emp->pais }}"
                                            data-tipo="{{ $emp->tipo }}">
                                        {{ $emp->nombre }} ({{ $emp->razon_social }}) — {{ $emp->tipo }} [{{ $emp->rfc ?? $emp->id_fiscal }}]
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="btnCargarEmpresaImportador" class="btn btn-primary fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Usar Empresa Seleccionada
                            </button>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <label class="form-label">RFC <span class="text-danger">*</span></label>
                        <input type="text" name="pedimento[rfc_importador]"
                               class="form-control fc @error('pedimento.rfc_importador') is-invalid @enderror"
                               value="{{ old('pedimento.rfc_importador', $p?->rfc_importador) }}"
                               maxlength="13" required style="text-transform:uppercase">
                        @error('pedimento.rfc_importador')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">CURP</label>
                        <input type="text" name="pedimento[curp_importador]" class="form-control fc"
                               value="{{ old('pedimento.curp_importador', $p?->curp_importador) }}" maxlength="18" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre / Razón Social <span class="text-danger">*</span></label>
                        <input type="text" name="pedimento[nombre_importador]"
                               class="form-control @error('pedimento.nombre_importador') is-invalid @enderror"
                               value="{{ old('pedimento.nombre_importador', $p?->nombre_importador) }}" maxlength="200" required>
                        @error('pedimento.nombre_importador')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Domicilio Fiscal <span class="text-danger">*</span></label>
                        <input type="text" name="pedimento[domicilio_importador]"
                               class="form-control @error('pedimento.domicilio_importador') is-invalid @enderror"
                               value="{{ old('pedimento.domicilio_importador', $p?->domicilio_importador) }}" maxlength="400" required
                               placeholder="Calle, Núm. Ext., Núm. Int., Colonia, Municipio, C.P., Estado, País">
                        @error('pedimento.domicilio_importador')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- ── 4. VALORES ───────────────────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-currency-dollar me-1"></i> Valores</span>
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    {{-- Valor Dólares: calculado = Val. Moneda Fact × Factor Moneda (si USD) o conversión --}}
                    <div class="col-md-4">
                        <label class="form-label label-auto">Valor Dólares (USD) <span class="badge-auto">Auto</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">USD</span>
                            <input type="number" name="pedimento[valor_dolares]" id="valorDolares" class="form-control fc campo-auto"
                                   value="{{ old('pedimento.valor_dolares', $p?->valor_dolares) }}" step="0.01" min="0" readonly
                                   title="Calculado: Val. Moneda Fact. × Factor Moneda (convertido a USD)">
                        </div>
                        <div class="form-text">= Val. Moneda Fact. × Factor Mon.</div>
                    </div>
                    {{-- Valor en Aduana: calculado = Valor Dólares × Tipo Cambio + Incrementables - Decrementables --}}
                    <div class="col-md-4">
                        <label class="form-label label-auto">Valor en Aduana (MXN) <span class="badge-auto">Auto</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" name="pedimento[valor_aduana]" id="valorAduana" class="form-control fc campo-auto"
                                   value="{{ old('pedimento.valor_aduana', $p?->valor_aduana) }}" step="0.01" min="0" readonly
                                   title="Calculado: (Val. USD × Tipo Cambio) + Incrementables − Decrementables">
                        </div>
                        <div class="form-text">= (USD × T.C.) + Incr. − Decr.</div>
                    </div>
                    {{-- Precio Pagado: calculado = Val. Moneda Fact × Factor Moneda × Tipo Cambio --}}
                    <div class="col-md-4">
                        <label class="form-label label-auto">Precio Pagado / Valor Comercial (MXN) <span class="badge-auto">Auto</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" name="pedimento[precio_pagado_valor_comercial]" id="precioPagado" class="form-control fc campo-auto"
                                   value="{{ old('pedimento.precio_pagado_valor_comercial', $p?->precio_pagado_valor_comercial) }}" step="0.01" min="0" readonly
                                   title="Calculado: Val. Moneda Fact. × Factor Moneda × Tipo Cambio">
                        </div>
                        <div class="form-text">= Val. Mon. Fact. × Factor × T.C.</div>
                    </div>

                    {{-- Incrementables --}}
                    <div class="col-12">
                        <div class="p-2 rounded" style="background:#f0f8f0;border:1px solid #bbf7d0">
                            <div class="mb-2" style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#166534;letter-spacing:.04em">
                                <i class="bi bi-arrow-up-circle me-1"></i> Valores Incrementables
                            </div>
                            <div class="row g-2">
                                @foreach(['val_seguros' => 'Val. Seguros', 'seguros' => 'Seguros', 'fletes' => 'Fletes', 'embalajes' => 'Embalajes', 'otros_incrementables' => 'Otros Incr.'] as $f => $label)
                                    <div class="col">
                                        <label class="form-label">{{ $label }}</label>
                                        <input type="number" name="pedimento[{{ $f }}]" class="form-control fc incr-field"
                                               value="{{ old("pedimento.{$f}", $p?->$f ?? 0) }}" step="0.01" min="0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Decrementables --}}
                    <div class="col-12">
                        <div class="p-2 rounded" style="background:#fff5f5;border:1px solid #fecaca">
                            <div class="mb-2" style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:#991b1b;letter-spacing:.04em">
                                <i class="bi bi-arrow-down-circle me-1"></i> Valores Decrementables
                            </div>
                            <div class="row g-2">
                                @foreach(['transporte_decrementables' => 'Transporte', 'seguro_decrementables' => 'Seguro', 'carga_decrementables' => 'Carga', 'descarga_decrementables' => 'Descarga', 'otros_decrementables' => 'Otros'] as $f => $l)
                                    <div class="col">
                                        <label class="form-label">{{ $l }}</label>
                                        <input type="number" name="pedimento[{{ $f }}]" class="form-control fc decr-field"
                                               value="{{ old("pedimento.{$f}", $p?->$f ?? 0) }}" step="0.01" min="0">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 5. FECHAS + IDENTIFICACIÓN / BULTOS ─────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-calendar3 me-1"></i> Fechas &amp; Identificación</span>
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Fecha Entrada</label>
                        <input type="date" name="pedimento[fecha_entrada]" class="form-control fc"
                               value="{{ old('pedimento.fecha_entrada', $p?->fecha_entrada?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Pago</label>
                        <input type="date" name="pedimento[fecha_pago]" class="form-control fc"
                               value="{{ old('pedimento.fecha_pago', $p?->fecha_pago?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label label-auto">Clave Secc. <span class="badge-auto">Auto</span></label>
                        <input type="text" name="pedimento[clave_seccion_aduanera]" id="claveSeccionAduanera" class="form-control fc campo-auto"
                               value="{{ old('pedimento.clave_seccion_aduanera', $p?->clave_seccion_aduanera) }}" readonly tabindex="-1" title="Se obtiene de Aduana E/S">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label label-auto">Nombre Aduana Despacho <span class="badge-auto">Auto</span></label>
                        <input type="text" name="pedimento[nombre_aduana_despacho]" id="nombreAduanaDespacho" class="form-control campo-auto"
                               value="{{ old('pedimento.nombre_aduana_despacho', $p?->nombre_aduana_despacho) }}" readonly tabindex="-1" title="Se obtiene de Aduana E/S">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Marcas, Números y Total de Bultos</label>
                        <input type="text" name="pedimento[marcas_numeros_bultos]" class="form-control fc"
                               value="{{ old('pedimento.marcas_numeros_bultos', $p?->marcas_numeros_bultos) }}" maxlength="200">
                    </div>
                </div>
            </div>

            {{-- ── 6. PROVEEDOR / COMPRADOR ─────────────────────────────────── --}}
            <div class="sec-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-shop me-1"></i> Datos del Proveedor / Comprador</span>
                @if(!empty($empresasRegistradas) && count($empresasRegistradas) > 0)
                    <span class="badge bg-info text-dark font-normal" style="font-size: .75rem; text-transform: none;">
                        <i class="bi bi-building-check me-1"></i> {{ count($empresasRegistradas) }} empresa(s) disponible(s)
                    </span>
                @endif
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    @if(!empty($empresasRegistradas) && count($empresasRegistradas) > 0)
                    <div class="col-12 mb-2">
                        <label class="form-label text-primary fw-bold">
                            <i class="bi bi-building-fill-down me-1"></i> Seleccionar Empresa Registrada para Proveedor / Comprador
                        </label>
                        <div class="input-group">
                            <select id="selectEmpresaProveedor" class="form-select border-primary" style="background-color: var(--bg-alt);">
                                <option value="">— Seleccionar de mis empresas registradas —</option>
                                @foreach($empresasRegistradas as $emp)
                                    <option value="{{ $emp->id }}"
                                            data-nombre="{{ $emp->nombre }}"
                                            data-razon="{{ $emp->razon_social }}"
                                            data-rfc="{{ $emp->rfc }}"
                                            data-id-fiscal="{{ $emp->id_fiscal }}"
                                            data-domicilio="{{ $emp->domicilio }}"
                                            data-pais="{{ $emp->pais }}"
                                            data-tipo="{{ $emp->tipo }}">
                                        {{ $emp->nombre }} ({{ $emp->razon_social }}) — {{ $emp->tipo }} [{{ $emp->id_fiscal ?? $emp->rfc }}]
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="btnCargarEmpresaProveedor" class="btn btn-primary fw-bold">
                                <i class="bi bi-check-circle-fill me-1"></i> Usar Empresa Seleccionada
                            </button>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">ID Fiscal (RFC / Tax ID)</label>
                        <input type="text" name="proveedor[id_fiscal]" class="form-control fc"
                               value="{{ old('proveedor.id_fiscal', $pv?->id_fiscal) }}" maxlength="50" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Nombre / Razón Social</label>
                        <input type="text" name="proveedor[nombre]" class="form-control"
                               value="{{ old('proveedor.nombre', $pv?->nombre) }}" maxlength="200">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Vinculación</label>
                        <select name="proveedor[vinculacion]" class="form-select">
                            <option value="NO" {{ old('proveedor.vinculacion', $pv?->vinculacion ?? 'NO') === 'NO' ? 'selected' : '' }}>NO</option>
                            <option value="SI" {{ old('proveedor.vinculacion', $pv?->vinculacion) === 'SI' ? 'selected' : '' }}>SÍ</option>
                        </select>
                        <div class="form-text">Art. 64-68 L.A.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Domicilio</label>
                        <input type="text" name="proveedor[domicilio]" class="form-control"
                               value="{{ old('proveedor.domicilio', $pv?->domicilio) }}" maxlength="400">
                    </div>
                </div>
            </div>

            {{-- ── 7. FACTURA ───────────────────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-receipt me-1"></i> Factura / Documento de Valor</span>
                <span class="badge-apendice">Art. 36-A fracc. I</span>
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Núm. CFDI / Documento Equivalente</label>
                        <input type="text" name="factura[num_cfdi]" class="form-control fc"
                               value="{{ old('factura.num_cfdi', $fv?->num_cfdi) }}" maxlength="60">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="factura[fecha]" class="form-control fc"
                               value="{{ old('factura.fecha', $fv?->fecha?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Incoterm</label>
                        <select name="factura[incoterm]" class="form-select">
                            <option value="" disabled hidden {{ !old('factura.incoterm', $fv?->incoterm) ? 'selected' : '' }}>— Seleccionar —</option>
                            @foreach(['EXW', 'FCA', 'CPT', 'CIP', 'DAP', 'DPU', 'DDP', 'FAS', 'FOB', 'CFR', 'CIF'] as $inc)
                                <option value="{{ $inc }}" {{ old('factura.incoterm', $fv?->incoterm) === $inc ? 'selected' : '' }}>{{ $inc }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Apéndice 21</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Moneda Factura</label>
                        <select name="factura[moneda_factura]" id="monedaFactura" class="form-select fc">
                            <option value="" disabled hidden {{ !old('factura.moneda_factura', $fv?->moneda_factura) ? 'selected' : '' }}>— —</option>
                            @foreach(['USD', 'MXN', 'EUR', 'GBP', 'JPY', 'CNY'] as $mon)
                                <option value="{{ $mon }}" {{ old('factura.moneda_factura', $fv?->moneda_factura) === $mon ? 'selected' : '' }}>{{ $mon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Val. Moneda Fact.</label>
                        <input type="number" name="factura[val_moneda_fact]" id="valMonedaFact" class="form-control fc"
                               value="{{ old('factura.val_moneda_fact', $fv?->val_moneda_fact ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Factor Moneda</label>
                        <input type="number" name="factura[factor_moneda]" id="factorMoneda" class="form-control fc"
                               value="{{ old('factura.factor_moneda', $fv?->factor_moneda ?? 1) }}" step="0.00000001" min="0">
                    </div>
                    {{-- Val. Dólares Factura: calculado = Val. Moneda Fact × Factor Moneda --}}
                    <div class="col-md-2">
                        <label class="form-label label-auto">Val. Dólares <span class="badge-auto">Auto</span></label>
                        <input type="number" name="factura[val_dolares]" id="valDolaresFact" class="form-control fc campo-auto"
                               value="{{ old('factura.val_dolares', $fv?->val_dolares ?? 0) }}" step="0.01" min="0" readonly
                               title="Calculado: Val. Moneda Fact. × Factor Moneda">
                        <div class="form-text">= Val. × Factor</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Núm. Guía / Orden Embarque</label>
                        <input type="text" name="factura[no_guia_embarque]" class="form-control fc"
                               value="{{ old('factura.no_guia_embarque', $fv?->no_guia_embarque) }}" maxlength="60">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ID Embarque</label>
                        <input type="text" name="factura[id_embarque]" class="form-control fc"
                               value="{{ old('factura.id_embarque', $fv?->id_embarque) }}" maxlength="60">
                    </div>
                </div>
            </div>

            {{-- ── 8. IDENTIFICADORES PEDIMENTO ────────────────────────────── --}}
            @php
                /* Apéndice 8 — Identificadores nivel G (pedimento) */
                $ap8 = [
                    'AC' => 'AC – Almacén general de depósito certificado',
                    'AE' => 'AE – Empresa de comercio exterior',
                    'AF' => 'AF – Activo fijo',
                    'AG' => 'AG – Almacén general de depósito fiscal',
                    'AI' => 'AI – Operaciones de comercio exterior con amparo',
                    'AP' => 'AP – Aplica pago virtual',
                    'AT' => 'AT – Aviso de tránsito',
                    'AV' => 'AV – Aviso electrónico de importación y exportación',
                    'A3' => 'A3 – Regularización de mercancías (importación definitiva)',
                    'BB' => 'BB – Exportación definitiva y retorno virtual',
                    'BR' => 'BR – Exportación temporal de mercancías fungibles y su retorno',
                    'CC' => 'CC – Carta de cupo',
                    'CF' => 'CF – Registro ante la Secretaría de Economía de empresas ubicadas en la franja o región fronteriza',
                    'CI' => 'CI – Certificación en materia de IVA e IEPS',
                    'CO' => 'CO – Condonación de créditos fiscales',
                    'CR' => 'CR – Recinto fiscalizado',
                    'CS' => 'CS – Copia simple',
                    'C5' => 'C5 – Depósito fiscal para la industria automotriz',
                    'DA' => 'DA – Despacho anticipado',
                    'DD' => 'DD – Despacho a domicilio a la exportación',
                    'DE' => 'DE – Desperdicios',
                    'DI' => 'DI – Documento de incrementable (CFDI o documento equivalente)',
                    'DN' => 'DN – Donación por parte de las empresas con programa IMMEX',
                    'ED' => 'ED – Documento digitalizado',
                    'EI' => 'EI – Autorización de depósito fiscal temporal para exposiciones internacionales de mercancías',
                    'EM' => 'EM – Empresa de mensajería y paquetería',
                    'EP' => 'EP – Declaración de CURP',
                    'FI' => 'FI – Factor de actualización con índice nacional de precios al consumidor',
                    'FR' => 'FR – Fecha que rige',
                    'FT' => 'FT – Folio de trámite generado por la Ventanilla Digital',
                    'FV' => 'FV – Factor de actualización con variación cambiaria',
                    'F8' => 'F8 – Depósito fiscal para exposición y venta (mercancías nacionales o nacionalizadas)',
                    'GS' => 'GS – Exportación temporal y retorno de dispositivos electrónicos que establece la regla 3.7.33',
                    'G9' => 'G9 – Transferencia de mercancías que se retiran de un recinto fiscalizado estratégico no colindante con la aduana, para importación definitiva de residentes en territorio nacional',
                    'HC' => 'HC – Operaciones del sector de hidrocarburos',
                    'IC' => 'IC – Empresa certificada',
                    'ID' => 'ID – Importación definitiva de vehículos o en franquicia diplomática con autorización de la AGJ',
                    'IF' => 'IF – Registro ante la Secretaría de Economía de empresas ubicadas en la región fronteriza de Chetumal',
                    'IM' => 'IM – Empresas con programa IMMEX',
                    'IR' => 'IR – Recinto fiscalizado estratégico',
                    'J4' => 'J4 – Retorno de mercancía de procedencia extranjera',
                    'LD' => 'LD – Despacho por lugar distinto',
                    'LR' => 'LR – Importación por pequeños contribuyentes',
                    'MD' => 'MD – Menaje de diplomáticos',
                    'MI' => 'MI – Importación definitiva de muestras amparadas bajo un protocolo de investigación',
                    'MJ' => 'MJ – Operaciones de empresas de mensajería y paquetería de mercancías no sujetas al pago de IGI e IVA',
                    'MS' => 'MS – Modalidad de servicios de empresas con programa IMMEX',
                    'MT' => 'MT – Monto total del valor en dólares a ejercer por mercancía textil',
                    'M7' => 'M7 – Opinión favorable de la SE',
                    'NR' => 'NR – Operación en la que las mercancías no ingresan a recinto fiscalizado',
                    'OC' => 'OC – Operación tramitada en fase de contingencia',
                    'OE' => 'OE – Operador Económico Autorizado',
                    'PC' => 'PC – Pedimento consolidado',
                    'PD' => 'PD – Parte II',
                    'PH' => 'PH – Pedimento electrónico simplificado',
                    'PI' => 'PI – Inspección previa',
                    'PL' => 'PL – Preliberación de mercancías',
                    'PP' => 'PP – Programa de promoción sectorial',
                    'PZ' => 'PZ – Ampliación del plazo para el retorno de mercancía importada o exportada temporalmente',
                    'RC' => 'RC – Consecutivo de CFDI, documentos equivalentes o remesas',
                    'RD' => 'RD – Retorno a depósito fiscal de la industria automotriz de mercancía exportada en definitiva',
                    'RL' => 'RL – Responsable solidario',
                    'RO' => 'RO – Revisión en origen por parte de empresas certificadas',
                    'RQ' => 'RQ – Importación definitiva de remolques, semirremolques y portacontenedores',
                    'RT' => 'RT – Reexpedición por terceros',
                    'RV' => 'RV – Regularización de vehículos usados',
                    'SF' => 'SF – Clave de unidad autorizada del almacén general de depósito',
                    'SH' => 'SH – Autorización del SAT',
                    'SO' => 'SO – Socio Comercial Certificado',
                    'ST' => 'ST – Operaciones sujetas al artículo 2.5 del T-MEC',
                    'SU' => 'SU – Operaciones sujetas a los artículos 14 del Anexo III de la Decisión, 15 del Anexo I del TLCAELC o al ACC',
                    'TB' => 'TB – Aviso de tránsito interno cuya carga se va a consolidar',
                    'TD' => 'TD – Tipo de desistimiento y retorno',
                    'TI' => 'TI – Tránsito interfroterizo',
                    'TM' => 'TM – Tránsito internacional',
                    'TR' => 'TR – Traspaso de mercancías en depósito fiscal',
                    'TU' => 'TU – Transferencia de mercancías (operaciones virtuales), con pedimento único',
                    'UP' => 'UP – Unidades prototipo',
                    'VC' => 'VC – Importación definitiva de vehículos usados en el Estado de Chihuahua',
                    'VF' => 'VF – Importación definitiva de vehículos usados a la franja o región fronteriza norte',
                    'VJ' => 'VJ – Fronterización de vehículos',
                    'VN' => 'VN – Importación definitiva de vehículos nuevos',
                    'VU' => 'VU – Importación definitiva de vehículos usados',
                    'V1' => 'V1 – Transferencias de mercancías',
                    'V2' => 'V2 – Transferencia de mercancías importadas con cuenta aduanera',
                    'V3' => 'V3 – Extracción de depósito fiscal de bienes para su retorno o exportación virtual (IA)',
                    'V4' => 'V4 – Retorno virtual derivado de la constancia de transferencia de mercancías',
                    'V5' => 'V5 – Transferencias de mercancías de empresas certificadas a residentes territorio nacional para su importación definitiva',
                    'V6' => 'V6 – Transferencias de mercancías sujetas a cupo',
                    'V7' => 'V7 – Transferencias del sector azucarero',
                    'V8' => 'V8 – Transferencias de mercancías extranjeras, nacionales y nacionalizadas de tiendas libres de impuestos (Duty Free)',
                    'V9' => 'V9 – Transferencias de mercancías por donación',
                    'XL' => 'XL – Presentación de mercancía en transporte sobredimensionado',
                    'XV' => 'XV – Exportación de vehículos de la industria automotriz terminal o manufacturera de vehículos de autotransporte',
                ];
            @endphp

            <div class="sec-header">
                <span><i class="bi bi-key me-1"></i> Identificadores del Pedimento</span>
                <span class="badge-apendice">Apéndice 8 – Nivel G</span>
            </div>
            <div class="sec-body">
                <div class="table-responsive mb-2">
                    <table class="table table-sm table-bordered mb-0" id="identsTabla" style="font-size:.78rem;min-width:700px">
                        <thead style="background:var(--adu-dark);color:#fff">
                            <tr>
                                <th style="font-size:.68rem;border-color:#1e2d45;width:260px">
                                    Clave / Compl. Identificador <span style="color:#94a3b8;font-weight:400">(Apéndice 8)</span>
                                </th>
                                <th style="font-size:.68rem;border-color:#1e2d45">Complemento 1</th>
                                <th style="font-size:.68rem;border-color:#1e2d45">Complemento 2</th>
                                <th style="font-size:.68rem;border-color:#1e2d45">Complemento 3</th>
                                <th style="width:42px;border-color:#1e2d45"></th>
                            </tr>
                        </thead>
                        <tbody id="identsTbody">
                            @forelse(old('identificadores', $ids) as $i => $ident)
                            <tr class="ident-fila">
                                <td>
                                    <select name="identificadores[{{ $i }}][clave]"
                                            class="form-select form-select-sm border-0 ident-clave" style="font-size:.78rem">
                                        <option value="" disabled hidden {{ !($ident['clave'] ?? '') ? 'selected' : '' }}>— Seleccionar —</option>
                                        @foreach($ap8 as $val => $lbl)
                                            <option value="{{ $val }}" {{ ($ident['clave'] ?? '') === $val ? 'selected' : '' }}>{{ $val }} – {{ explode(' – ', $lbl, 2)[1] ?? $lbl }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="identificadores[{{ $i }}][complemento1]" class="form-control form-control-sm fc border-0" value="{{ $ident['complemento1'] ?? '' }}" maxlength="100"></td>
                                <td><input type="text" name="identificadores[{{ $i }}][complemento2]" class="form-control form-control-sm fc border-0" value="{{ $ident['complemento2'] ?? '' }}" maxlength="100"></td>
                                <td><input type="text" name="identificadores[{{ $i }}][complemento3]" class="form-control form-control-sm fc border-0" value="{{ $ident['complemento3'] ?? '' }}" maxlength="100"></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ident px-1 py-0" title="Eliminar">
                                        <i class="bi bi-trash" style="font-size:.7rem"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr class="ident-fila">
                                <td>
                                    <select name="identificadores[0][clave]"
                                            class="form-select form-select-sm border-0 ident-clave" style="font-size:.78rem">
                                        <option value="" disabled hidden selected>— Seleccionar —</option>
                                        @foreach($ap8 as $val => $lbl)
                                            <option value="{{ $val }}">{{ $val }} – {{ explode(' – ', $lbl, 2)[1] ?? $lbl }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="identificadores[0][complemento1]" class="form-control form-control-sm fc border-0" maxlength="100"></td>
                                <td><input type="text" name="identificadores[0][complemento2]" class="form-control form-control-sm fc border-0" maxlength="100"></td>
                                <td><input type="text" name="identificadores[0][complemento3]" class="form-control form-control-sm fc border-0" maxlength="100"></td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ident px-1 py-0" title="Eliminar">
                                        <i class="bi bi-trash" style="font-size:.7rem"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mb-1" id="btnAddIdent">
                    <i class="bi bi-plus-lg me-1"></i> Agregar Identificador
                </button>
            </div>

            {{-- ── 9. TASAS A NIVEL PEDIMENTO ──────────────────────────────── --}}
            @php
                /* Apéndice 12 — Contribuciones (nivel G = pedimento) */
                $ap12contrib = [
                    'DTA'     => '1 – DTA – Derecho de trámite aduanero',
                    'C.C.'    => '2 – C.C. – Cuotas compensatorias',
                    'IVA'     => '3 – IVA – Impuesto al Valor Agregado',
                    'ISAN'    => '4 – ISAN – Impuesto sobre Automóviles Nuevos',
                    'IGI/IGE' => '6 – IGI/IGE – Imp. General de Importación/Exportación',
                    'REC.'    => '7 – REC. – Recargos',
                    'OTROS'   => '9 – OTROS – Otros',
                    'MULT.'   => '11 – MULT. – Multas',
                    '2.5'     => '12 – 2.5 – Contribuciones Art. 2.5 T-MEC',
                    'RT'      => '13 – RT – Recargos Art. 2.5 T-MEC',
                    'PRV'     => '15 – PRV – Prevalidación',
                    'EUR'     => '16 – EUR – Contribuciones TLCAELC/ACC',
                    'REU'     => '17 – REU – Recargos TLCAELC/ACC',
                    'MT'      => '20 – MT – Medida de transición',
                    'IEPS'    => '22 – IEPS – Gasolina (Art. 2 LIEPS)',
                    'IVA/PRV' => '23 – IVA/PRV – IVA prevalidación Art. 16-A',
                    '2IB'     => '24 – 2IB – IEPS alcohol/mieles',
                    '2IA2'    => '25 – 2IA2 – IEPS bebidas alcohólicas',
                    '2IA1'    => '26 – 2IA1 – IEPS cerveza',
                    '2IC'     => '27 – 2IC – IEPS tabacos labrados',
                    '2IF'     => '28 – 2IF – IEPS bebidas energetizantes',
                    '2IG'     => '29 – 2IG – IEPS bebidas saborizadas',
                    '2IJ'     => '30 – 2IJ – IEPS alimentos no básicos',
                    '2II'     => '31 – 2II – IEPS plaguicidas',
                    'ICF'     => '32 – ICF – IEPS combustibles fósiles',
                    'IEPSDIE' => '33 – IEPSDIE – IEPS diésel',
                    'ICNF'    => '34 – ICNF – IEPS combustibles no fósiles',
                    'LIEPS'   => '35 – LIEPS – IEPS otros',
                    'DFC'     => '50 – DFC – Diferencia a favor del contribuyente',
                ];
                /* Apéndice 18 — Tipos de tasa */
                $ap18 = [
                    '1'  => '1 – Porcentual',
                    '2'  => '2 – Específico',
                    '3'  => '3 – Cuota mínima (DTA)',
                    '4'  => '4 – Cuota fija',
                    '5'  => '5 – Tasa de descuento sobre ad valorem',
                    '6'  => '6 – Factor de aplicación sobre TIGIE',
                    '7'  => '7 – Al millar (DTA)',
                    '8'  => '8 – Tasa de descuento sobre el arancel específico',
                    '9'  => '9 – Tasa específica sobre precios de referencia',
                    '10' => '10 – Tasa específica sobre precios de referencia con UM',
                ];
            @endphp

            <div class="sec-header">
                <span><i class="bi bi-percent me-1"></i> Tasas a Nivel Pedimento</span>
                <span class="badge-apendice">Apéndices 12 y 18</span>
            </div>
            <div class="sec-body">
                <div class="table-responsive mb-2">
                    <table class="table table-sm table-bordered mb-0" id="tasasTabla" style="font-size:.78rem;min-width:500px">
                        <thead style="background:var(--adu-dark);color:#fff">
                            <tr>
                                <th style="font-size:.68rem;border-color:#1e2d45">
                                    Contrib. <span style="color:#94a3b8;font-weight:400">(Apéndice 12)</span>
                                </th>
                                <th style="font-size:.68rem;border-color:#1e2d45;width:220px">
                                    Cve. T. Tasa <span style="color:#94a3b8;font-weight:400">(Apéndice 18)</span>
                                </th>
                                <th style="font-size:.68rem;border-color:#1e2d45;width:130px">Tasa</th>
                                <th style="width:42px;border-color:#1e2d45"></th>
                            </tr>
                        </thead>
                        <tbody id="tasasTbody">
                            @forelse(old('tasas', $ts) as $i => $tasa)
                            <tr class="tasa-fila">
                                <td>
                                    <select name="tasas[{{ $i }}][contribucion]"
                                            class="form-select form-select-sm border-0 tasa-contrib" style="font-size:.78rem">
                                        <option value="" disabled hidden {{ !($tasa['nombre_contribucion'] ?? $tasa['contribucion'] ?? '') ? 'selected' : '' }}>— Seleccionar —</option>
                                        @foreach($ap12contrib as $val => $lbl)
                                            <option value="{{ $val }}" {{ ($tasa['nombre_contribucion'] ?? $tasa['contribucion'] ?? '') === $val ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="tasas[{{ $i }}][cve_tipo_tasa]"
                                            class="form-select form-select-sm border-0 tasa-tipo" style="font-size:.78rem">
                                        <option value="" disabled hidden {{ !($tasa['cve_tipo_tasa'] ?? '') ? 'selected' : '' }}>— —</option>
                                        @foreach($ap18 as $val => $lbl)
                                            <option value="{{ $val }}" {{ ($tasa['cve_tipo_tasa'] ?? '') === $val ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="tasas[{{ $i }}][tasa]"
                                           class="form-control form-control-sm fc border-0"
                                           value="{{ $tasa['tasa'] ?? 0 }}" step="0.00001" min="0">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-tasa px-1 py-0" title="Eliminar">
                                        <i class="bi bi-trash" style="font-size:.7rem"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr class="tasa-fila">
                                <td>
                                    <select name="tasas[0][contribucion]"
                                            class="form-select form-select-sm border-0 tasa-contrib" style="font-size:.78rem">
                                        <option value="" disabled hidden selected>— Seleccionar —</option>
                                        @foreach($ap12contrib as $val => $lbl)
                                            <option value="{{ $val }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="tasas[0][cve_tipo_tasa]"
                                            class="form-select form-select-sm border-0 tasa-tipo" style="font-size:.78rem">
                                        <option value="" disabled hidden selected>— —</option>
                                        @foreach($ap18 as $val => $lbl)
                                            <option value="{{ $val }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="tasas[0][tasa]"
                                           class="form-control form-control-sm fc border-0"
                                           value="0" step="0.00001" min="0">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-tasa px-1 py-0" title="Eliminar">
                                        <i class="bi bi-trash" style="font-size:.7rem"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mb-1" id="btnAddTasa">
                    <i class="bi bi-plus-lg me-1"></i> Agregar Tasa
                </button>
            </div>

            {{-- ── 10. CUADRO DE LIQUIDACIÓN ────────────────────────────────── --}}
            @php
                /* Apéndice 12 — Contribuciones */
                $ap12 = [
                    'DTA'     => '1 – DTA – Derecho de trámite aduanero',
                    'C.C.'    => '2 – C.C. – Cuotas compensatorias',
                    'IVA'     => '3 – IVA – Impuesto al Valor Agregado',
                    'ISAN'    => '4 – ISAN – Impuesto sobre Automóviles Nuevos',
                    'IGI/IGE' => '6 – IGI/IGE – Imp. General de Importación/Exportación',
                    'REC.'    => '7 – REC. – Recargos',
                    'OTROS'   => '9 – OTROS – Otros',
                    'MULT.'   => '11 – MULT. – Multas',
                    '2.5'     => '12 – 2.5 – Contribuciones Art. 2.5 T-MEC',
                    'RT'      => '13 – RT – Recargos Art. 2.5 T-MEC',
                    'PRV'     => '15 – PRV – Prevalidación',
                    'EUR'     => '16 – EUR – Contribuciones TLCAELC/ACC',
                    'REU'     => '17 – REU – Recargos TLCAELC/ACC',
                    'MT'      => '20 – MT – Medida de transición',
                    'IEPS'    => '22 – IEPS – Gasolina (Art. 2 LIEPS)',
                    'IVA/PRV' => '23 – IVA/PRV – IVA prevalidación Art. 16-A',
                    '2IB'     => '24 – 2IB – IEPS alcohol/mieles',
                    '2IA2'    => '25 – 2IA2 – IEPS bebidas alcohólicas',
                    '2IA1'    => '26 – 2IA1 – IEPS cerveza',
                    '2IC'     => '27 – 2IC – IEPS tabacos labrados',
                    '2IF'     => '28 – 2IF – IEPS bebidas energetizantes',
                    '2IG'     => '29 – 2IG – IEPS bebidas saborizadas',
                    '2IJ'     => '30 – 2IJ – IEPS alimentos no básicos',
                    '2II'     => '31 – 2II – IEPS plaguicidas',
                    'ICF'     => '32 – ICF – IEPS combustibles fósiles',
                    'IEPSDIE' => '33 – IEPSDIE – IEPS diésel',
                    'ICNF'    => '34 – ICNF – IEPS combustibles no fósiles',
                    'LIEPS'   => '35 – LIEPS – IEPS otros',
                    'DFC'     => '50 – DFC – Diferencia a favor del contribuyente',
                ];
                /* Apéndice 13 — Formas de pago */
                $ap13 = [
                    '0'  => '0 – Efectivo',
                    '2'  => '2 – Fianza',
                    '4'  => '4 – Depósito en cuenta aduanera',
                    '5'  => '5 – Temporal no sujeta a impuestos',
                    '6'  => '6 – Pendiente de pago',
                    '7'  => '7 – Cargo a partida presupuestal Gobierno Federal',
                    '8'  => '8 – Franquicia',
                    '9'  => '9 – Exento de pago',
                    '12' => '12 – Compensación',
                    '13' => '13 – Pago ya efectuado',
                    '14' => '14 – Condonaciones',
                    '15' => '15 – Cuentas aduaneras de garantía por precios estimados',
                    '16' => '16 – Acreditamiento',
                    '18' => '18 – Estímulo fiscal',
                    '19' => '19 – Otros medios de garantía',
                    '21' => '21 – Crédito en IVA e IEPS',
                    '22' => '22 – Garantía en IVA e IEPS',
                ];
                /* Filas existentes (para edición) */
                $liqFilas = old('liquidacion_filas', $p?->cuadroLiquidacion?->filas ?? []);
            @endphp

            <div class="sec-header">
                <span><i class="bi bi-table me-1"></i> Cuadro de Liquidación</span>
                <span class="badge-apendice">Apéndices 12 y 13 – Anexo 22</span>
            </div>
            <div class="sec-body">

                {{-- Tabla dinámica de conceptos --}}
                <div class="table-responsive mb-2">
                    <table class="table table-sm table-bordered mb-0" id="liqTabla" style="font-size:.78rem;min-width:700px">
                        <thead style="background:var(--adu-dark);color:#fff">
                            <tr>
                                <th style="font-size:.68rem;border-color:#1e2d45;min-width:230px">Concepto <span style="color:#94a3b8;font-weight:400">(Apéndice 12)</span></th>
                                <th style="width:160px;font-size:.68rem;border-color:#1e2d45">F.P. <span style="color:#94a3b8;font-weight:400">(Apéndice 13)</span></th>
                                <th style="width:150px;font-size:.68rem;border-color:#1e2d45">Importe ($)</th>
                                <th style="width:42px;border-color:#1e2d45"></th>
                            </tr>
                        </thead>
                        <tbody id="liqTbody">
                            @forelse($liqFilas as $fi => $fila)
                            <tr class="liq-fila">
                                <td>
                                    <select name="liquidacion_filas[{{ $fi }}][concepto]"
                                            class="form-select form-select-sm border-0 liq-concepto" style="font-size:.78rem">
                                        <option value="" disabled hidden {{ !($fila['concepto'] ?? '') ? 'selected' : '' }}>— Seleccionar —</option>
                                        @foreach($ap12 as $val => $lbl)
                                            <option value="{{ $val }}" {{ ($fila['concepto'] ?? '') === $val ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="liquidacion_filas[{{ $fi }}][fp]"
                                            class="form-select form-select-sm border-0 liq-fp" style="font-size:.78rem">
                                        <option value="" disabled hidden {{ !isset($fila['fp']) ? 'selected' : '' }}>— —</option>
                                        @foreach($ap13 as $val => $lbl)
                                            <option value="{{ $val }}" {{ (string)($fila['fp'] ?? '') === $val ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="liquidacion_filas[{{ $fi }}][importe]"
                                           class="form-control form-control-sm fc border-0 liq-importe-fila"
                                           value="{{ $fila['importe'] ?? 0 }}" step="0.01" min="0">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-liq px-1 py-0" title="Eliminar">
                                        <i class="bi bi-trash" style="font-size:.7rem"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            {{-- Fila inicial vacía --}}
                            <tr class="liq-fila">
                                <td>
                                    <select name="liquidacion_filas[0][concepto]"
                                            class="form-select form-select-sm border-0 liq-concepto" style="font-size:.78rem">
                                        <option value="" disabled hidden selected>— Seleccionar —</option>
                                        @foreach($ap12 as $val => $lbl)
                                            <option value="{{ $val }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="liquidacion_filas[0][fp]"
                                            class="form-select form-select-sm border-0 liq-fp" style="font-size:.78rem">
                                        <option value="" disabled hidden selected>— —</option>
                                        @foreach($ap13 as $val => $lbl)
                                            <option value="{{ $val }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="liquidacion_filas[0][importe]"
                                           class="form-control form-control-sm fc border-0 liq-importe-fila"
                                           value="0" step="0.01" min="0">
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-liq px-1 py-0" title="Eliminar">
                                        <i class="bi bi-trash" style="font-size:.7rem"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Botón agregar fila --}}
                <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="btnAddLiqFila">
                    <i class="bi bi-plus-lg me-1"></i> Agregar Concepto
                </button>

                {{-- Totales --}}
                <div class="d-flex justify-content-end gap-2">
                    <div style="min-width:140px">
                        <label class="form-label">Efectivo ($)</label>
                        <input type="number" name="liquidacion[efectivo]" id="liqEfectivo" class="form-control fc fw-bold"
                               value="{{ old('liquidacion.efectivo', $liq?->efectivo ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div style="min-width:140px">
                        <label class="form-label">Otros ($)</label>
                        <input type="number" name="liquidacion[otros]" id="liqOtros" class="form-control fc"
                               value="{{ old('liquidacion.otros', $liq?->otros ?? 0) }}" step="0.01" min="0">
                    </div>
                    <div style="min-width:180px">
                        <label class="form-label label-auto" style="color:var(--adu-gold)">
                            Total ($) <span class="badge-auto">Auto</span>
                        </label>
                        <input type="number" name="liquidacion[total]" id="liqTotal" class="form-control fc total-input"
                               value="{{ old('liquidacion.total', $liq?->total ?? 0) }}" step="0.01" min="0" readonly
                               title="Suma de todos los importes del cuadro de liquidación">
                    </div>
                </div>
                <div class="form-text mt-1 text-end" style="color:#0369a1">
                    <i class="bi bi-info-circle me-1"></i>
                    Total = suma de todos los importes capturados · Efectivo y Otros se clasifican por forma de pago
                </div>
            </div>

            {{-- ── 11. DEPÓSITO REFERENCIADO ────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-credit-card-2-front me-1"></i> Depósito Referenciado – Línea de Captura</span>
                <span class="badge-apendice">Apéndice 23</span>
            </div>
            <div class="sec-body">
                <div class="linea-captura-display mb-3" id="lineaCapturaDisplay">
                    {{ old('pago.linea_captura', $pag?->linea_captura ?? '— Línea de captura —') }}
                </div>
                <div class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label">Patente</label>
                        <input type="text" name="pago[patente]" id="pagoPatente" class="form-control fc"
                               value="{{ old('pago.patente', $pag?->patente) }}" maxlength="10">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Aduana</label>
                        <input type="text" name="pago[aduana]" id="pagoAduana" class="form-control fc"
                               value="{{ old('pago.aduana', $pag?->aduana) }}" maxlength="10">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Institución Bancaria</label>
                        <select name="pago[nombre_institucion]" class="form-select">
                            <option value="" disabled hidden {{ !old('pago.nombre_institucion', $pag?->nombre_institucion) ? 'selected' : '' }}>— Seleccionar —</option>
                            @foreach(['BBVA Bancomer, S.A.', 'Banco Nacional de México, S.A.', 'HSBC México, S.A.', 'Santander México, S.A.', 'Banorte, S.A.', 'Scotiabank Inverlat, S.A.', 'Inbursa, S.A.'] as $banco)
                                <option value="{{ $banco }}" {{ old('pago.nombre_institucion', $pag?->nombre_institucion) === $banco ? 'selected' : '' }}>{{ $banco }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Línea de Captura</label>
                        <input type="text" name="pago[linea_captura]" id="pagoLineaCaptura" class="form-control fc"
                               value="{{ old('pago.linea_captura', $pag?->linea_captura) }}" maxlength="50">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Importe Pagado ($)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" name="pago[importe_pagado]" id="pagoImportePagado" class="form-control fc fw-bold"
                                   value="{{ old('pago.importe_pagado', $pag?->importe_pagado ?? 0) }}" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha de Pago</label>
                        <input type="date" name="pago[fecha_pago]" class="form-control fc"
                               value="{{ old('pago.fecha_pago', $pag?->fecha_pago?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Núm. Operación Bancaria</label>
                        <input type="text" name="pago[num_operacion_bancaria]" class="form-control fc"
                               value="{{ old('pago.num_operacion_bancaria', $pag?->num_operacion_bancaria) }}" maxlength="50">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Núm. Transacción SAT</label>
                        <input type="text" name="pago[num_transaccion_sat]" class="form-control fc"
                               value="{{ old('pago.num_transaccion_sat', $pag?->num_transaccion_sat) }}" maxlength="50">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Medio Presentación</label>
                        <select name="pago[medio_presentacion]" class="form-select">
                            <option value="" disabled hidden {{ !old('pago.medio_presentacion', $pag?->medio_presentacion) ? 'selected' : '' }}>— Seleccionar —</option>
                            <option value="OTROS MEDIOS ELECTRÓNICOS (PAGO ELECTRÓNICO)" {{ old('pago.medio_presentacion', $pag?->medio_presentacion) === 'OTROS MEDIOS ELECTRÓNICOS (PAGO ELECTRÓNICO)' ? 'selected' : '' }}>Medios Electrónicos</option>
                            <option value="VENTANILLA BANCARIA" {{ old('pago.medio_presentacion', $pag?->medio_presentacion) === 'VENTANILLA BANCARIA' ? 'selected' : '' }}>Ventanilla Bancaria</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Medio Recepción / Cobro</label>
                        <select name="pago[medio_recepcion_cobro]" class="form-select">
                            <option value="" disabled hidden {{ !old('pago.medio_recepcion_cobro', $pag?->medio_recepcion_cobro) ? 'selected' : '' }}>— Seleccionar —</option>
                            <option value="EFECTIVO (CARGO A CUENTA)" {{ old('pago.medio_recepcion_cobro', $pag?->medio_recepcion_cobro) === 'EFECTIVO (CARGO A CUENTA)' ? 'selected' : '' }}>Efectivo (Cargo a Cuenta)</option>
                            <option value="TRANSFERENCIA ELECTRÓNICA" {{ old('pago.medio_recepcion_cobro', $pag?->medio_recepcion_cobro) === 'TRANSFERENCIA ELECTRÓNICA' ? 'selected' : '' }}>Transferencia Electrónica</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── 12. AGENTE ADUANAL ───────────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-person-badge me-1"></i> Agente / Agencia Aduanal</span>
                <span class="badge-apendice">Art. 81 L.A.</span>
            </div>
            <div class="sec-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="agente[nombre]" class="form-control" value="{{ old('agente.nombre', $ag?->nombre) }}" maxlength="200">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Razón Social</label>
                        <input type="text" name="agente[razon_social]" class="form-control" value="{{ old('agente.razon_social', $ag?->razon_social) }}" maxlength="200">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">RFC</label>
                        <input type="text" name="agente[rfc]" class="form-control fc" value="{{ old('agente.rfc', $ag?->rfc) }}" maxlength="13" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Patente</label>
                        <input type="text" name="agente[patente]" class="form-control fc" value="{{ old('agente.patente', $ag?->patente) }}" maxlength="10">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">CURP</label>
                        <input type="text" name="agente[curp]" class="form-control fc" value="{{ old('agente.curp', $ag?->curp) }}" maxlength="18" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Núm. Serie Certificado (e.firma)</label>
                        <input type="text" name="agente[num_serie_certificado]" class="form-control fc" value="{{ old('agente.num_serie_certificado', $ag?->num_serie_certificado) }}" maxlength="50">
                    </div>
                    <div class="col-12">
                        <label class="form-label">e.firma (cadena digital)</label>
                        <textarea name="agente[efirma]" class="form-control fc" rows="2" style="font-size:.7rem">{{ old('agente.efirma', $ag?->efirma) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── 13. OBSERVACIONES ────────────────────────────────────────── --}}
            <div class="sec-header">
                <span><i class="bi bi-chat-left-text me-1"></i> Observaciones</span>
            </div>
            <div class="sec-body">
                <textarea name="pedimento[observaciones]" class="form-control" rows="3"
                          placeholder="Ingrese observaciones pertinentes...">{{ old('pedimento.observaciones', $p?->observaciones) }}</textarea>
            </div>


            {{-- ══════════════════════════════════════════════════════════════
                 ── 14. PARTIDAS (ANEXO DEL PEDIMENTO) ────────────────────────
                 ══════════════════════════════════════════════════════════════ --}}
            @php
                $ap7 = ['1'=>'1 – Kilo','2'=>'2 – Gramo','3'=>'3 – Metro lineal','4'=>'4 – Metro cuadrado','5'=>'5 – Metro cúbico','6'=>'6 – Pieza','7'=>'7 – Cabeza','8'=>'8 – Litro','9'=>'9 – Par','10'=>'10 – Kilowatt','11'=>'11 – Millar','12'=>'12 – Juego','13'=>'13 – Kilowatt/Hora','14'=>'14 – Tonelada','15'=>'15 – Barril','16'=>'16 – Gramo neto','17'=>'17 – Decenas','18'=>'18 – Cientos','19'=>'19 – Docenas','20'=>'20 – Caja','21'=>'21 – Botella','22'=>'22 – Carat'];
                $ap11 = ['0'=>'0 – Valor comercial (exportación)','1'=>'1 – Valor de transacción de las mercancías','2'=>'2 – Valor de transacción de mercancías idénticas','3'=>'3 – Valor de transacción de mercancías similares','4'=>'4 – Valor de precio unitario de venta','5'=>'5 – Valor reconstruido','6'=>'6 – Último recurso'];
                $ap4 = ['A4'=>'A4 – DEU – Alemania','B4'=>'B4 – ARG – Argentina','B5'=>'B5 – AUS – Australia','C2'=>'C2 – BEL – Bélgica','C8'=>'C8 – BRA – Brasil','D9'=>'D9 – CAN – Canadá','F6'=>'F6 – CHL – Chile','Z3'=>'Z3 – CHN – China','E4'=>'E4 – COL – Colombia','F2'=>'F2 – CRI – Costa Rica','G7'=>'G7 – ESP – España','G8'=>'G8 – USA – Estados Unidos','H5'=>'H5 – FRA – Francia','I6'=>'I6 – GTM – Guatemala','J5'=>'J5 – HND – Honduras','J6'=>'J6 – HKG – Hong Kong','J8'=>'J8 – IND – India','J9'=>'J9 – IDN – Indonesia','K6'=>'K6 – ITA – Italia','K9'=>'K9 – JPN – Japón','E8'=>'E8 – KOR – Corea del Sur','N3'=>'N3 – MEX – México','J4'=>'J4 – NLD – Países Bajos','Q8'=>'Q8 – PAN – Panamá','R2'=>'R2 – PER – Perú','R5'=>'R5 – POL – Polonia','R6'=>'R6 – PRT – Portugal','R9'=>'R9 – GBR – Reino Unido','RU'=>'RU – RUS – Rusia','U8'=>'U8 – CHE – Suiza','F7'=>'F7 – TWN – Taiwán','V1'=>'V1 – THA – Tailandia','W4'=>'W4 – TUR – Turquía','W9'=>'W9 – VNM – Vietnam','Z9'=>'Z9 – KCD – No declarado'];
                $ap12p = ['C.C.'=>'2 – C.C.','IVA'=>'3 – IVA','ISAN'=>'4 – ISAN','IGI/IGE'=>'6 – IGI/IGE','2.5'=>'12 – 2.5','EUR'=>'16 – EUR','MT'=>'20 – MT','IEPS'=>'22 – IEPS','2IB'=>'24 – 2IB','2IA2'=>'25 – 2IA2','2IA1'=>'26 – 2IA1','2IC'=>'27 – 2IC','2IF'=>'28 – 2IF','2IG'=>'29 – 2IG','2IJ'=>'30 – 2IJ','2II'=>'31 – 2II','ICF'=>'32 – ICF','IEPSDIE'=>'33 – IEPSDIE','ICNF'=>'34 – ICNF','LIEPS'=>'35 – LIEPS'];
                $ap8p = [
                    'AL' => 'AL – Mercancía originaria importada al amparo de ALADI',
                    'AR' => 'AR – Consulta arancelaria',
                    'B2' => 'B2 – Bienes del artículo 2 de la Ley del IEPS',
                    'CD' => 'CD – Certificado con dispensa temporal',
                    'CE' => 'CE – Certificado de elegibilidad',
                    'CF' => 'CF – Preferencia arancelaria para empresas ubicadas en la franja o región fronteriza',
                    'DC' => 'DC – Clasificación del cupo',
                    'DH' => 'DH – Datos de importación de hidrocarburos',
                    'DP' => 'DP – Introducción y extracción de depósito fiscal para exposición y venta de artículos promocionales',
                    'DR' => 'DR – Rectificación por discrepancia documental',
                    'DS' => 'DS – Destrucción de mercancías en depósito fiscal para la exposición y venta',
                    'DT' => 'DT – Operaciones sujetas al artículo 2.5 del T-MEC',
                    'DU' => 'DU – Operaciones sujetas a los arts. 14 del Anexo III de la Decisión, 15 del Anexo I del TLCAELC o al ACC',
                    'DV' => 'DV – Venta de mercancías a misiones diplomáticas y consulares cuando cuente con franquicia diplomática',
                    'EA' => 'EA – Excepción de aviso automático de importación/exportación',
                    'EB' => 'EB – Envases y empaques',
                    'EC' => 'EC – Excepción de pago de cuota compensatoria',
                    'EF' => 'EF – Estímulo fiscal',
                    'EN' => 'EN – No aplicación de la Norma Oficial Mexicana',
                    'EO' => 'EO – Emisor del certificado de origen',
                    'EP' => 'EP – Excepción de inscripción al padrón de importadores',
                    'ES' => 'ES – Estado de la mercancía',
                    'EX' => 'EX – Exención de cuenta aduanera de garantía',
                    'FC' => 'FC – Fracción correlacionada',
                    'GA' => 'GA – Cuenta aduanera de garantía',
                    'GI' => 'GI – Garantía IMMEX',
                    'HI' => 'HI – Tipo de gasolina',
                    'IA' => 'IA – Certificado de aprobación para producción de partes aeronáuticas',
                    'IF' => 'IF – Preferencia arancelaria para empresas ubicadas en la región fronteriza de Chetumal',
                    'II' => 'II – Inventario inicial de empresas denominadas Duty Free',
                    'IN' => 'IN – Incidencia',
                    'IS' => 'IS – Mercancías exentas de impuestos al comercio exterior',
                    'LP' => 'LP – Lista de escaso abasto',
                    'MA' => 'MA – Embalajes de madera',
                    'MB' => 'MB – Marbetes y/o precintos',
                    'MC' => 'MC – Marca',
                    'ME' => 'ME – Material de ensamble',
                    'MM' => 'MM – Importación definitiva de muestras y muestrarios',
                    'MR' => 'MR – Registro para la toma de muestras, peligrosas o para las que se requiera de instalaciones o equipos especiales para la toma de las mismas',
                    'MV' => 'MV – Año-modelo del vehículo',
                    'NA' => 'NA – Mercancías con preferencia arancelaria ALADI señaladas en el Acuerdo',
                    'NE' => 'NE – Excepción de cumplir con el Anexo 21',
                    'NS' => 'NS – Excepción de inscripción en los padrones de importadores y exportadores-sectoriales',
                    'NT' => 'NT – Nota de Tratado',
                    'NZ' => 'NZ – Mercancía que no se ha beneficiado del "Sugar Re-Export Program" de los Estados Unidos de América',
                    'OM' => 'OM – Mercancía originaria de México',
                    'OV' => 'OV – Operación vulnerable',
                    'PA' => 'PA – Cumplimiento de la Norma Oficial Mexicana, para verificarse en un almacén general de depósito autorizado',
                    'PB' => 'PB – Cumplimiento de NOM para su verificación dentro del territorio nacional, en un domicilio particular',
                    'PG' => 'PG – Mercancía peligrosa',
                    'PM' => 'PM – Presentación de la mercancía',
                    'PO' => 'PO – Proveedor de origen',
                    'PR' => 'PR – Proporción determinada',
                    'PS' => 'PS – Sector autorizado al amparo de PROSEC',
                    'PT' => 'PT – Exportación o retorno de producto terminado',
                    'PV' => 'PV – Prueba de valor',
                    'RA' => 'RA – Retorno de racks',
                    'RF' => 'RF – Cuota compensatoria basada en precios de referencia',
                    'RP' => 'RP – Retorno de residuos peligrosos generados por empresas con programa IMMEX',
                    'SB' => 'SB – Importación de organismos genéticamente modificados',
                    'SC' => 'SC – Excepción de pago de medida de transición',
                    'SH' => 'SH – Autorización del SAT',
                    'SM' => 'SM – Excepción de la declaración de marbetes',
                    'TA' => 'TA – Régimen de transición alternativo',
                    'TB' => 'TB – Tránsito interno por aduanas y mercancías específicas',
                    'TC' => 'TC – Correlación de las fracciones arancelarias',
                    'TL' => 'TL – Mercancía originaria al amparo de Tratados de Libre Comercio',
                    'TV' => 'TV – Total de mercancía extraída de depósito fiscal',
                    'UM' => 'UM – Uso de la mercancía',
                    'VT' => 'VT – Importación de autobuses, camiones y tractocamiones usados para el transporte de personas y mercancías',
                    'XP' => 'XP – Excepción al cumplimiento de regulaciones y restricciones no arancelarias',
                    'ZC' => 'ZC – Contenido de azúcar',
                ];
                $partidas = old('partidas', $p?->partidas?->toArray() ?? []);
            @endphp

            <div class="sec-header" style="background:var(--adu-dark);color:#fff;border-color:var(--adu-dark)">
                <span><i class="bi bi-list-ol me-1"></i> Anexo del Pedimento — Partidas</span>
                <span style="font-size:.6rem;font-weight:400;color:#94a3b8">Campos 1–25 · Anexo 22 RGCE 2024</span>
            </div>

            <div id="partidasContainer">
            @forelse($partidas as $pi => $pt)
                <div class="partida-bloque" data-pi="{{ $pi }}" style="border-bottom:2px solid #c8d8ed">

                    <div class="d-flex justify-content-between align-items-center px-3 py-1" style="background:#e8f0f8;border-bottom:1px solid #c8d8ed">
                        <span style="font-size:.72rem;font-weight:700;color:var(--adu-blue);text-transform:uppercase;letter-spacing:.05em">
                            <i class="bi bi-tag me-1"></i> Partida <span class="partida-sec-label">{{ str_pad($pi+1,3,'0',STR_PAD_LEFT) }}</span>
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-partida py-0 px-2" style="font-size:.68rem">
                            <i class="bi bi-trash me-1"></i> Eliminar partida
                        </button>
                    </div>

                    <div class="sec-body">

                        {{-- Fila 1: SEC · Fracción · SUBD · VINC · MET VAL --}}
                        <div class="row g-2 mb-2">
                            <div class="col-md-1">
                                <label class="form-label label-auto">SEC. <span class="badge-auto">Auto</span></label>
                                <input type="text" name="partidas[{{ $pi }}][sec]" class="form-control fc campo-auto text-center"
                                       value="{{ str_pad($pi+1,3,'0',STR_PAD_LEFT) }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Fracción <span class="text-danger">*</span></label>
                                <input type="text" name="partidas[{{ $pi }}][fraccion]" class="form-control fc"
                                       value="{{ old("partidas.$pi.fraccion", $pt['fraccion'] ?? '') }}" maxlength="8" placeholder="00000000">
                                <div class="form-text">TIGIE – 8 dígitos</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">SUBD / Núm. Ident. Comercial</label>
                                <input type="text" name="partidas[{{ $pi }}][subd_nico]" class="form-control fc"
                                       value="{{ old("partidas.$pi.subd_nico", $pt['subd_nico'] ?? '') }}" maxlength="20">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">VINC.</label>
                                <select name="partidas[{{ $pi }}][vinc]" class="form-select">
                                    <option value="" disabled hidden {{ !($pt['vinc'] ?? '') ? 'selected':'' }}>— —</option>
                                    <option value="0" {{ ($pt['vinc'] ?? '') == '0' ? 'selected':'' }}>0 – Sin vinculación</option>
                                    <option value="1" {{ ($pt['vinc'] ?? '') == '1' ? 'selected':'' }}>1 – Sí, no afecta valor</option>
                                    <option value="2" {{ ($pt['vinc'] ?? '') == '2' ? 'selected':'' }}>2 – Sí, afecta valor</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Método Valoración <span class="text-danger">*</span></label>
                                <select name="partidas[{{ $pi }}][met_val]" class="form-select">
                                    <option value="" disabled hidden {{ !($pt['met_val'] ?? '') ? 'selected':'' }}>— Apéndice 11 —</option>
                                    @foreach($ap11 as $val => $lbl)
                                        <option value="{{ $val }}" {{ ($pt['met_val'] ?? '') == $val ? 'selected':'' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Apéndice 11</div>
                            </div>
                        </div>

                        {{-- Fila 2: UMC · Cant. UMC · UMT · Cant. UMT · P.V/C · P.O/D --}}
                        <div class="row g-2 mb-2">
                            <div class="col-md-2">
                                <label class="form-label">UMC <span class="text-danger">*</span></label>
                                <select name="partidas[{{ $pi }}][umc]" class="form-select">
                                    <option value="" disabled hidden {{ !($pt['umc'] ?? '') ? 'selected':'' }}>— Apéndice 7 —</option>
                                    @foreach($ap7 as $val => $lbl)
                                        <option value="{{ $val }}" {{ ($pt['umc'] ?? '') == $val ? 'selected':'' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Apéndice 7</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cantidad UMC <span class="text-danger">*</span></label>
                                <input type="number" name="partidas[{{ $pi }}][cantidad_umc]"
                                       class="form-control fc partida-cant-umc"
                                       value="{{ old("partidas.$pi.cantidad_umc", $pt['cantidad_umc'] ?? 0) }}"
                                       step="0.000001" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">UMT</label>
                                <select name="partidas[{{ $pi }}][umt]" class="form-select">
                                    <option value="" disabled hidden {{ !($pt['umt'] ?? '') ? 'selected':'' }}>— Apéndice 7 —</option>
                                    @foreach($ap7 as $val => $lbl)
                                        <option value="{{ $val }}" {{ ($pt['umt'] ?? '') == $val ? 'selected':'' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Apéndice 7</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cantidad UMT</label>
                                <input type="number" name="partidas[{{ $pi }}][cantidad_umt]"
                                       class="form-control fc"
                                       value="{{ old("partidas.$pi.cantidad_umt", $pt['cantidad_umt'] ?? 0) }}"
                                       step="0.000001" min="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">P. V/C</label>
                                <select name="partidas[{{ $pi }}][p_vc]" class="form-select">
                                    <option value="" disabled hidden {{ !($pt['p_vc'] ?? '') ? 'selected':'' }}>— País —</option>
                                    @foreach($ap4 as $val => $lbl)
                                        <option value="{{ $val }}" {{ ($pt['p_vc'] ?? '') == $val ? 'selected':'' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Apéndice 4</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">P. O/D</label>
                                <select name="partidas[{{ $pi }}][p_od]" class="form-select">
                                    <option value="" disabled hidden {{ !($pt['p_od'] ?? '') ? 'selected':'' }}>— País —</option>
                                    @foreach($ap4 as $val => $lbl)
                                        <option value="{{ $val }}" {{ ($pt['p_od'] ?? '') == $val ? 'selected':'' }}>{{ $val }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Apéndice 4</div>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="form-label">Descripción de la Mercancía <span class="text-danger">*</span></label>
                                <textarea name="partidas[{{ $pi }}][descripcion]" class="form-control fc" rows="2"
                                          placeholder="Naturaleza y características técnicas y comerciales necesarias para la clasificación arancelaria...">{{ old("partidas.$pi.descripcion", $pt['descripcion'] ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- Valores --}}
                        <div class="row g-2 mb-2">
                            <div class="col-md-3">
                                <label class="form-label label-auto">Imp. Precio Pag. / Val. Comercial <span class="badge-auto">Auto</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="partidas[{{ $pi }}][imp_precio_pag]"
                                           class="form-control fc campo-auto partida-imp-pag"
                                           value="{{ old("partidas.$pi.imp_precio_pag", $pt['imp_precio_pag'] ?? 0) }}"
                                           step="0.01" min="0" readonly
                                           title="Val. Adu/USD × Tipo de Cambio">
                                </div>
                                <div class="form-text">Campo 14 = Val. USD × T.C.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Val. Adu / Val. USD <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="partidas[{{ $pi }}][val_adu_usd]"
                                           class="form-control fc partida-val-adu"
                                           value="{{ old("partidas.$pi.val_adu_usd", $pt['val_adu_usd'] ?? 0) }}"
                                           step="0.01" min="0">
                                </div>
                                <div class="form-text">Valor en aduana / USD por partida</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Precio Unit. <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="partidas[{{ $pi }}][precio_unit]"
                                           class="form-control fc partida-precio-unit"
                                           value="{{ old("partidas.$pi.precio_unit", $pt['precio_unit'] ?? 0) }}"
                                           step="0.000001" min="0"
                                           title="Precio pagado ÷ Cantidad UMC">
                                </div>
                                <div class="form-text">Precio pag. ÷ Cant. UMC</div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Val. Agregado</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="partidas[{{ $pi }}][val_agreg]"
                                           class="form-control fc"
                                           value="{{ old("partidas.$pi.val_agreg", $pt['val_agreg'] ?? 0) }}"
                                           step="0.01" min="0">
                                </div>
                                <div class="form-text">Solo IMMEX/maquila</div>
                            </div>
                        </div>

                        {{-- Marca · Modelo · Código --}}
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Marca</label>
                                <input type="text" name="partidas[{{ $pi }}][marca]" class="form-control fc"
                                       value="{{ old("partidas.$pi.marca", $pt['marca'] ?? '') }}" maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Modelo</label>
                                <input type="text" name="partidas[{{ $pi }}][modelo]" class="form-control fc"
                                       value="{{ old("partidas.$pi.modelo", $pt['modelo'] ?? '') }}" maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Código Producto</label>
                                <input type="text" name="partidas[{{ $pi }}][codigo_producto]" class="form-control fc"
                                       value="{{ old("partidas.$pi.codigo_producto", $pt['codigo_producto'] ?? '') }}" maxlength="50">
                            </div>
                        </div>

                        {{-- Contribuciones nivel Partida --}}
                        <div class="mb-2 p-2 rounded" style="background:#f8fafc;border:1px solid #e2eaf4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--adu-blue);letter-spacing:.04em">
                                    <i class="bi bi-percent me-1"></i> Contribuciones a Nivel Partida
                                    <span style="font-size:.6rem;color:#64748b;font-weight:400"> CON · TASA · T.T. · F.P. · IMPORTE (Campos 21-25)</span>
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-add-contrib-partida"
                                        style="font-size:.68rem" data-pi="{{ $pi }}">
                                    <i class="bi bi-plus-lg me-1"></i> Agregar
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="font-size:.75rem">
                                    <thead style="background:var(--adu-dark);color:#fff">
                                        <tr>
                                            <th style="font-size:.65rem;border-color:#1e2d45">CON.</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45;width:100px">Tasa</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45;width:120px">T.T. (Ap. 18)</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45;width:90px">F.P. (Ap. 13)</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45;width:120px">Importe ($) <span style="color:#7dd3fc">⚙</span></th>
                                            <th style="width:36px;border-color:#1e2d45"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="contrib-partida-tbody" data-pi="{{ $pi }}">
                                        @forelse(old("partidas.$pi.contribs", $pt['contribs'] ?? []) as $ci => $contrib)
                                        <tr class="contrib-partida-fila">
                                            <td><select name="partidas[{{ $pi }}][contribs][{{ $ci }}][con]" class="form-select form-select-sm border-0 cp-con" style="font-size:.75rem">
                                                <option value="" disabled hidden {{ !($contrib['con'] ?? '') ? 'selected':'' }}>— —</option>
                                                @foreach($ap12p as $v => $l) <option value="{{ $v }}" {{ ($contrib['con'] ?? '') == $v ? 'selected':'' }}>{{ $v }}</option> @endforeach
                                            </select></td>
                                            <td><input type="number" name="partidas[{{ $pi }}][contribs][{{ $ci }}][tasa]" class="form-control form-control-sm fc border-0 cp-tasa" value="{{ $contrib['tasa'] ?? 0 }}" step="0.00001" min="0"></td>
                                            <td><select name="partidas[{{ $pi }}][contribs][{{ $ci }}][tt]" class="form-select form-select-sm border-0 cp-tt" style="font-size:.75rem">
                                                <option value="" disabled hidden {{ !($contrib['tt'] ?? '') ? 'selected':'' }}>— —</option>
                                                @foreach($ap18 as $v => $l) <option value="{{ $v }}" {{ ($contrib['tt'] ?? '') == $v ? 'selected':'' }}>{{ $v }}</option> @endforeach
                                            </select></td>
                                            <td><select name="partidas[{{ $pi }}][contribs][{{ $ci }}][fp]" class="form-select form-select-sm border-0 cp-fp" style="font-size:.75rem">
                                                <option value="" disabled hidden {{ !($contrib['fp'] ?? '') ? 'selected':'' }}>— —</option>
                                                @foreach($ap13 as $v => $l) <option value="{{ $v }}" {{ ($contrib['fp'] ?? '') == $v ? 'selected':'' }}>{{ $v }}</option> @endforeach
                                            </select></td>
                                            <td><input type="number" name="partidas[{{ $pi }}][contribs][{{ $ci }}][importe]" class="form-control form-control-sm fc border-0 campo-auto cp-importe" value="{{ $contrib['importe'] ?? 0 }}" step="0.01" min="0" readonly title="Calculado automáticamente"></td>
                                            <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-contrib-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button></td>
                                        </tr>
                                        @empty
                                        <tr class="contrib-partida-fila">
                                            <td><select name="partidas[{{ $pi }}][contribs][0][con]" class="form-select form-select-sm border-0 cp-con" style="font-size:.75rem">
                                                <option value="" disabled hidden selected>— —</option>
                                                @foreach($ap12p as $v => $l) <option value="{{ $v }}">{{ $v }}</option> @endforeach
                                            </select></td>
                                            <td><input type="number" name="partidas[{{ $pi }}][contribs][0][tasa]" class="form-control form-control-sm fc border-0 cp-tasa" value="0" step="0.00001" min="0"></td>
                                            <td><select name="partidas[{{ $pi }}][contribs][0][tt]" class="form-select form-select-sm border-0 cp-tt" style="font-size:.75rem">
                                                <option value="" disabled hidden selected>— —</option>
                                                @foreach($ap18 as $v => $l) <option value="{{ $v }}">{{ $v }}</option> @endforeach
                                            </select></td>
                                            <td><select name="partidas[{{ $pi }}][contribs][0][fp]" class="form-select form-select-sm border-0 cp-fp" style="font-size:.75rem">
                                                <option value="" disabled hidden selected>— —</option>
                                                @foreach($ap13 as $v => $l) <option value="{{ $v }}">{{ $v }}</option> @endforeach
                                            </select></td>
                                            <td><input type="number" name="partidas[{{ $pi }}][contribs][0][importe]" class="form-control form-control-sm fc border-0 campo-auto cp-importe" value="0" step="0.01" min="0" readonly></td>
                                            <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-contrib-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button></td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Identificadores nivel Partida --}}
                        <div class="mb-2 p-2 rounded" style="background:#f8fafc;border:1px solid #e2eaf4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--adu-blue);letter-spacing:.04em">
                                    <i class="bi bi-key me-1"></i> Identificadores a Nivel Partida
                                    <span style="font-size:.6rem;color:#64748b;font-weight:400"> (Apéndice 8 – Nivel P)</span>
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-add-ident-partida"
                                        style="font-size:.68rem" data-pi="{{ $pi }}">
                                    <i class="bi bi-plus-lg me-1"></i> Agregar
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="font-size:.75rem">
                                    <thead style="background:var(--adu-dark);color:#fff">
                                        <tr>
                                            <th style="font-size:.65rem;border-color:#1e2d45;width:240px">IDENTIF. (Apéndice 8 – P)</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45">Complemento 1</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45">Complemento 2</th>
                                            <th style="font-size:.65rem;border-color:#1e2d45">Complemento 3</th>
                                            <th style="width:36px;border-color:#1e2d45"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="ident-partida-tbody" data-pi="{{ $pi }}">
                                        @forelse(old("partidas.$pi.idents", $pt['idents'] ?? []) as $ii => $ident)
                                        <tr class="ident-partida-fila">
                                            <td><select name="partidas[{{ $pi }}][idents][{{ $ii }}][clave]" class="form-select form-select-sm border-0 ip-clave" style="font-size:.75rem">
                                                <option value="" disabled hidden {{ !($ident['clave'] ?? '') ? 'selected':'' }}>— —</option>
                                                @foreach($ap8p as $v => $l) <option value="{{ $v }}" {{ ($ident['clave'] ?? '') == $v ? 'selected':'' }}>{{ $v }} – {{ explode(' – ',$l,2)[1] ?? '' }}</option> @endforeach
                                            </select></td>
                                            <td><input type="text" name="partidas[{{ $pi }}][idents][{{ $ii }}][c1]" class="form-control form-control-sm fc border-0" value="{{ $ident['c1'] ?? '' }}" maxlength="100"></td>
                                            <td><input type="text" name="partidas[{{ $pi }}][idents][{{ $ii }}][c2]" class="form-control form-control-sm fc border-0" value="{{ $ident['c2'] ?? '' }}" maxlength="100"></td>
                                            <td><input type="text" name="partidas[{{ $pi }}][idents][{{ $ii }}][c3]" class="form-control form-control-sm fc border-0" value="{{ $ident['c3'] ?? '' }}" maxlength="100"></td>
                                            <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-ident-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button></td>
                                        </tr>
                                        @empty
                                        <tr class="ident-partida-fila">
                                            <td><select name="partidas[{{ $pi }}][idents][0][clave]" class="form-select form-select-sm border-0 ip-clave" style="font-size:.75rem">
                                                <option value="" disabled hidden selected>— —</option>
                                                @foreach($ap8p as $v => $l) <option value="{{ $v }}">{{ $v }} – {{ explode(' – ',$l,2)[1] ?? '' }}</option> @endforeach
                                            </select></td>
                                            <td><input type="text" name="partidas[{{ $pi }}][idents][0][c1]" class="form-control form-control-sm fc border-0" maxlength="100"></td>
                                            <td><input type="text" name="partidas[{{ $pi }}][idents][0][c2]" class="form-control form-control-sm fc border-0" maxlength="100"></td>
                                            <td><input type="text" name="partidas[{{ $pi }}][idents][0][c3]" class="form-control form-control-sm fc border-0" maxlength="100"></td>
                                            <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-ident-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button></td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Observaciones nivel partida --}}
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label">Observaciones a Nivel Partida</label>
                                <textarea name="partidas[{{ $pi }}][obs_partida]" class="form-control fc" rows="2"
                                          placeholder="Datos adicionales a nivel partida...">{{ old("partidas.$pi.obs_partida", $pt['obs_partida'] ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>{{-- /sec-body --}}
                </div>{{-- /partida-bloque --}}
            @empty
                <div id="partidasVacias" class="text-center py-4" style="color:#94a3b8;font-size:.82rem">
                    <i class="bi bi-inbox me-2" style="font-size:1.4rem"></i><br>Aún no hay partidas. Usa el botón para agregar la primera.
                </div>
            @endforelse
            </div>{{-- /partidasContainer --}}

            <div class="sec-body pt-2 pb-3">
                <button type="button" class="btn btn-primary btn-sm" id="btnAddPartida">
                    <i class="bi bi-plus-lg me-1"></i> Agregar Partida
                </button>
                <span class="ms-2" style="font-size:.68rem;color:#64748b">Cada partida = una fracción arancelaria diferente</span>
            </div>

        </div>{{-- /card-body --}}
    </div>{{-- /tarjeta única --}}

    {{-- BOTONES --}}
    <div class="d-flex justify-content-between pb-4">
        <a href="{{ route('pedimentos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Cancelar
        </a>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-save me-1"></i> Guardar Borrador
            </button>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send me-1"></i> {{ $p ? 'Actualizar' : 'Crear Pedimento' }}
            </button>
        </div>
    </div>

</form>

{{-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT — Cálculos automáticos en tiempo real
     ══════════════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Helpers ─────────────────────────────────────────────────
    const num  = id => parseFloat(document.getElementById(id)?.value) || 0;
    const set  = (id, val) => {
        const el = document.getElementById(id);
        if (el) el.value = isNaN(val) ? 0 : Math.round(val * 100) / 100;
    };
    const sumClass = cls => [...document.querySelectorAll('.' + cls)]
        .reduce((s, el) => s + (parseFloat(el.value) || 0), 0);

    // ── 1. Referencia desde Iniciales de Empresa Registrada (1ra letra de 2 primeras palabras) + Ops. Año / Pedimento Año ──
    const proveedorNombreInput   = document.querySelector('[name="proveedor[nombre]"]');
    const proveedorIdFiscalInput = document.querySelector('[name="proveedor[id_fiscal]"]');
    const proveedorDomInput      = document.querySelector('[name="proveedor[domicilio]"]');

    const importadorNombreInput  = document.querySelector('[name="pedimento[nombre_importador]"]');
    const importadorRfcInput     = document.querySelector('[name="pedimento[rfc_importador]"]');
    const importadorDomInput     = document.querySelector('[name="pedimento[domicilio_importador]"]');

    const selectEmpresaImportador = document.getElementById('selectEmpresaRegistrada');
    const selectEmpresaProveedor  = document.getElementById('selectEmpresaProveedor');
    const btnCargarImportador     = document.getElementById('btnCargarEmpresaImportador');
    const btnCargarProveedor      = document.getElementById('btnCargarEmpresaProveedor');
    const campoReferencia         = document.getElementById('campoReferencia');

    const totalOpsAnio = '{{ str_pad($totalOperacionesAnio ?? 1, 3, "0", STR_PAD_LEFT) }}';
    const numPedAnio   = '{{ str_pad($numPedimentoAnio ?? 1, 3, "0", STR_PAD_LEFT) }}';

    function getEmpresaInitials(name) {
        if (!name) return 'IV';
        // Extraer palabras quitando caracteres especiales
        const words = name.trim().split(/\s+/).map(w => w.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]/g, '')).filter(w => w.length > 0);
        if (words.length >= 2) {
            // Toma la primera letra de las dos primeras palabras del nombre de la empresa
            return (words[0].charAt(0) + words[1].charAt(0)).toUpperCase();
        } else if (words.length === 1) {
            if (words[0].length >= 2) {
                return words[0].substring(0, 2).toUpperCase();
            } else if (words[0].length === 1) {
                return (words[0].charAt(0) + 'X').toUpperCase();
            }
        }
        return 'IV';
    }

    function calcReferencia() {
        if (!campoReferencia) return;

        let rawName = '';
        if (selectEmpresaProveedor && selectEmpresaProveedor.selectedIndex > 0) {
            const opt = selectEmpresaProveedor.options[selectEmpresaProveedor.selectedIndex];
            rawName = opt.getAttribute('data-nombre') || opt.getAttribute('data-razon') || '';
        }
        if (!rawName && selectEmpresaImportador && selectEmpresaImportador.selectedIndex > 0) {
            const opt = selectEmpresaImportador.options[selectEmpresaImportador.selectedIndex];
            rawName = opt.getAttribute('data-nombre') || opt.getAttribute('data-razon') || '';
        }
        if (!rawName) {
            rawName = (proveedorNombreInput?.value || importadorNombreInput?.value || '').trim();
        }

        const prefix = getEmpresaInitials(rawName);
        campoReferencia.value = prefix + totalOpsAnio + '/' + numPedAnio;
    }

    function cargarDatosEmpresa(selectElement, target) {
        if (!selectElement || selectElement.selectedIndex <= 0) return;
        const opt = selectElement.options[selectElement.selectedIndex];
        if (!opt || !opt.value) return;

        const nombre = opt.getAttribute('data-nombre') || opt.getAttribute('data-razon') || '';
        const rfc = opt.getAttribute('data-rfc') || opt.getAttribute('data-id-fiscal') || '';
        const idFiscal = opt.getAttribute('data-id-fiscal') || opt.getAttribute('data-rfc') || '';
        const domicilio = opt.getAttribute('data-domicilio') || '';

        if (target === 'importador' || target === 'ambos') {
            if (importadorNombreInput) importadorNombreInput.value = nombre;
            if (importadorRfcInput) importadorRfcInput.value = rfc;
            if (importadorDomInput) importadorDomInput.value = domicilio;
        }

        if (target === 'proveedor' || target === 'ambos') {
            if (proveedorNombreInput) proveedorNombreInput.value = nombre;
            if (proveedorIdFiscalInput) proveedorIdFiscalInput.value = idFiscal;
            if (proveedorDomInput) proveedorDomInput.value = domicilio;
        }

        calcReferencia();
    }

    if (selectEmpresaImportador) {
        selectEmpresaImportador.addEventListener('change', () => cargarDatosEmpresa(selectEmpresaImportador, 'importador'));
    }
    if (btnCargarImportador) {
        btnCargarImportador.addEventListener('click', () => cargarDatosEmpresa(selectEmpresaImportador, 'importador'));
    }

    if (selectEmpresaProveedor) {
        selectEmpresaProveedor.addEventListener('change', () => cargarDatosEmpresa(selectEmpresaProveedor, 'proveedor'));
    }
    if (btnCargarProveedor) {
        btnCargarProveedor.addEventListener('click', () => cargarDatosEmpresa(selectEmpresaProveedor, 'proveedor'));
    }

    proveedorNombreInput?.addEventListener('input', calcReferencia);
    importadorNombreInput?.addEventListener('input', calcReferencia);
    if (!campoReferencia?.value) {
        calcReferencia();
    }

    // ── 1.b Sincronizar Aduana E/S con Clave y Nombre Despacho ──
    const aduanaES = document.getElementById('aduanaES');
    function syncAduanaDespacho() {
        if (!aduanaES) return;
        const cve = document.getElementById('claveSeccionAduanera');
        const nombre = document.getElementById('nombreAduanaDespacho');
        if (aduanaES.value) {
            if (cve) cve.value = aduanaES.value;
            if (nombre) {
                // The option text is like "470 - Aer. Int. CDMX"
                const selectText = aduanaES.options[aduanaES.selectedIndex].text;
                // Get the string after the dash
                const parts = selectText.split(' – ');
                nombre.value = parts.length > 1 ? parts[1].toUpperCase() : selectText;
            }
        } else {
            if (cve) cve.value = '';
            if (nombre) nombre.value = '';
        }
    }
    aduanaES?.addEventListener('change', syncAduanaDespacho);
    // Sincronizar en carga inicial solo si están vacíos o aduanaES tiene valor y el valor coincide
    syncAduanaDespacho();

    // ── 2. Val. Dólares Factura ──────────────────────────────────
    function calcValDolaresFact() {
        set('valDolaresFact', num('valMonedaFact') * num('factorMoneda'));
        calcValores();
    }

    // ── 3. Valores cabecera ──────────────────────────────────────
    function calcValores() {
        const tc     = num('tipoCambio');
        const valUSD = num('valDolaresFact');
        const incr   = sumClass('incr-field');
        const decr   = sumClass('decr-field');
        set('valorDolares', valUSD);
        set('precioPagado', num('valMonedaFact') * num('factorMoneda') * tc);
        set('valorAduana',  valUSD * tc + incr - decr);
    }

    // ── 4. Total Liquidación ─────────────────────────────────────
    function calcTotalLiq() {
        let totalGeneral  = 0;
        let totalEfectivo = 0;
        document.querySelectorAll('#liqTbody .liq-fila').forEach(tr => {
            const importe = parseFloat(tr.querySelector('.liq-importe-fila')?.value) || 0;
            const fp      = tr.querySelector('.liq-fp')?.value;
            totalGeneral += importe;
            if (String(fp) === '0') totalEfectivo += importe;
        });
        set('liqTotal', totalGeneral);
        const elEf = document.getElementById('liqEfectivo');
        if (elEf) elEf.value = Math.round(totalEfectivo * 100) / 100;
        const elOt = document.getElementById('liqOtros');
        if (elOt) elOt.value = Math.round((totalGeneral - totalEfectivo) * 100) / 100;
    }

    // ── 5. Cuadro de Liquidación — filas dinámicas ───────────────
    const AP12 = {
        'DTA':'DTA','C.C.':'C.C.','IVA':'IVA','ISAN':'ISAN','IGI/IGE':'IGI/IGE',
        'REC.':'REC.','OTROS':'OTROS','MULT.':'MULT.','2.5':'2.5','RT':'RT',
        'PRV':'PRV','EUR':'EUR','REU':'REU','MT':'MT','IEPS':'IEPS',
        'IVA/PRV':'IVA/PRV','2IB':'2IB','2IA2':'2IA2','2IA1':'2IA1','2IC':'2IC',
        '2IF':'2IF','2IG':'2IG','2IJ':'2IJ','2II':'2II','ICF':'ICF',
        'IEPSDIE':'IEPSDIE','ICNF':'ICNF','LIEPS':'LIEPS','DFC':'DFC'
    };
    const AP13 = {
        '0':'0','2':'2','4':'4','5':'5','6':'6','7':'7','8':'8','9':'9',
        '12':'12','13':'13','14':'14','15':'15','16':'16','18':'18','19':'19','21':'21','22':'22'
    };

    function buildSel(name, opts, cls) {
        const sel = document.createElement('select');
        sel.name = name; sel.className = `form-select form-select-sm border-0 ${cls}`; sel.style.fontSize = '.78rem';
        const o0 = new Option('— —', ''); o0.disabled = o0.hidden = o0.selected = true; sel.append(o0);
        Object.keys(opts).forEach(v => sel.append(new Option(v, v)));
        return sel;
    }

    function attachLiqRow(tr) {
        tr.querySelector('.liq-importe-fila')?.addEventListener('input', calcTotalLiq);
        tr.querySelector('.liq-fp')?.addEventListener('change', calcTotalLiq);
        tr.querySelector('.btn-remove-liq')?.addEventListener('click', () => { tr.remove(); reindexRows('liqTbody','liq-fila',['liq-concepto','liq-fp','liq-importe-fila'],['concepto','fp','importe'],'liquidacion_filas'); calcTotalLiq(); });
    }

    function reindexRows(tbodyId, rowCls, selCls, fields, prefix) {
        document.querySelectorAll(`#${tbodyId} .${rowCls}`).forEach((tr, i) => {
            selCls.forEach((cls, j) => {
                const el = tr.querySelector(`.${cls}`);
                if (el) el.name = `${prefix}[${i}][${fields[j]}]`;
            });
        });
    }

    document.getElementById('btnAddLiqFila')?.addEventListener('click', () => {
        const idx = document.querySelectorAll('#liqTbody .liq-fila').length;
        const tr  = document.createElement('tr'); tr.className = 'liq-fila';
        const tc1 = document.createElement('td'); tc1.append(buildSel(`liquidacion_filas[${idx}][concepto]`, AP12, 'liq-concepto'));
        const tc2 = document.createElement('td'); tc2.append(buildSel(`liquidacion_filas[${idx}][fp]`, AP13, 'liq-fp'));
        const tc3 = document.createElement('td');
        const inp = document.createElement('input');
        inp.type='number'; inp.name=`liquidacion_filas[${idx}][importe]`; inp.className='form-control form-control-sm fc border-0 liq-importe-fila'; inp.value='0'; inp.step='0.01'; inp.min='0';
        tc3.append(inp);
        const tc4 = document.createElement('td'); tc4.className='text-center align-middle';
        tc4.innerHTML=`<button type="button" class="btn btn-sm btn-outline-danger btn-remove-liq px-1 py-0"><i class="bi bi-trash" style="font-size:.7rem"></i></button>`;
        tr.append(tc1,tc2,tc3,tc4);
        document.getElementById('liqTbody').append(tr);
        attachLiqRow(tr);
    });

    document.querySelectorAll('#liqTbody .liq-fila').forEach(attachLiqRow);

    // ── 6. Identificadores — filas dinámicas ─────────────────────
    const AP8_keys = {
        'AC': 'Almacén general de depósito certificado',
        'AE': 'Empresa de comercio exterior',
        'AF': 'Activo fijo',
        'AG': 'Almacén general de depósito fiscal',
        'AI': 'Operaciones de comercio exterior con amparo',
        'AP': 'Aplica pago virtual',
        'AT': 'Aviso de tránsito',
        'AV': 'Aviso electrónico de importación y exportación',
        'A3': 'Regularización de mercancías (importación definitiva)',
        'BB': 'Exportación definitiva y retorno virtual',
        'BR': 'Exportación temporal de mercancías fungibles y su retorno',
        'CC': 'Carta de cupo',
        'CF': 'Registro ante la Secretaría de Economía de empresas ubicadas en la franja o región fronteriza',
        'CI': 'Certificación en materia de IVA e IEPS',
        'CO': 'Condonación de créditos fiscales',
        'CR': 'Recinto fiscalizado',
        'CS': 'Copia simple',
        'C5': 'Depósito fiscal para la industria automotriz',
        'DA': 'Despacho anticipado',
        'DD': 'Despacho a domicilio a la exportación',
        'DE': 'Desperdicios',
        'DI': 'Documento de incrementable (CFDI o documento equivalente)',
        'DN': 'Donación por parte de las empresas con programa IMMEX',
        'ED': 'Documento digitalizado',
        'EI': 'Autorización de depósito fiscal temporal para exposiciones internacionales de mercancías',
        'EM': 'Empresa de mensajería y paquetería',
        'EP': 'Declaración de CURP',
        'FI': 'Factor de actualización con índice nacional de precios al consumidor',
        'FR': 'Fecha que rige',
        'FT': 'Folio de trámite generado por la Ventanilla Digital',
        'FV': 'Factor de actualización con variación cambiaria',
        'F8': 'Depósito fiscal para exposición y venta (mercancías nacionales o nacionalizadas)',
        'GS': 'Exportación temporal y retorno de dispositivos electrónicos que establece la regla 3.7.33',
        'G9': 'Transferencia de mercancías que se retiran de un recinto fiscalizado estratégico no colindante con la aduana, para importación definitiva de residentes en territorio nacional',
        'HC': 'Operaciones del sector de hidrocarburos',
        'IC': 'Empresa certificada',
        'ID': 'Importación definitiva de vehículos o en franquicia diplomática con autorización de la AGJ',
        'IF': 'Registro ante la Secretaría de Economía de empresas ubicadas en la región fronteriza de Chetumal',
        'IM': 'Empresas con programa IMMEX',
        'IR': 'Recinto fiscalizado estratégico',
        'J4': 'Retorno de mercancía de procedencia extranjera',
        'LD': 'Despacho por lugar distinto',
        'LR': 'Importación por pequeños contribuyentes',
        'MD': 'Menaje de diplomáticos',
        'MI': 'Importación definitiva de muestras amparadas bajo un protocolo de investigación',
        'MJ': 'Operaciones de empresas de mensajería y paquetería de mercancías no sujetas al pago de IGI e IVA',
        'MS': 'Modalidad de servicios de empresas con programa IMMEX',
        'MT': 'Monto total del valor en dólares a ejercer por mercancía textil',
        'M7': 'Opinión favorable de la SE',
        'NR': 'Operación en la que las mercancías no ingresan a recinto fiscalizado',
        'OC': 'Operación tramitada en fase de contingencia',
        'OE': 'Operador Económico Autorizado',
        'PC': 'Pedimento consolidado',
        'PD': 'Parte II',
        'PH': 'Pedimento electrónico simplificado',
        'PI': 'Inspección previa',
        'PL': 'Preliberación de mercancías',
        'PP': 'Programa de promoción sectorial',
        'PZ': 'Ampliación del plazo para el retorno de mercancía importada o exportada temporalmente',
        'RC': 'Consecutivo de CFDI, documentos equivalentes o remesas',
        'RD': 'Retorno a depósito fiscal de la industria automotriz de mercancía exportada en definitiva',
        'RL': 'Responsable solidario',
        'RO': 'Revisión en origen por parte de empresas certificadas',
        'RQ': 'Importación definitiva de remolques, semirremolques y portacontenedores',
        'RT': 'Reexpedición por terceros',
        'RV': 'Regularización de vehículos usados',
        'SF': 'Clave de unidad autorizada del almacén general de depósito',
        'SH': 'Autorización del SAT',
        'SO': 'Socio Comercial Certificado',
        'ST': 'Operaciones sujetas al artículo 2.5 del T-MEC',
        'SU': 'Operaciones sujetas a los artículos 14 del Anexo III de la Decisión, 15 del Anexo I del TLCAELC o al ACC',
        'TB': 'Aviso de tránsito interno cuya carga se va a consolidar',
        'TD': 'Tipo de desistimiento y retorno',
        'TI': 'Tránsito interfroterizo',
        'TM': 'Tránsito internacional',
        'TR': 'Traspaso de mercancías en depósito fiscal',
        'TU': 'Transferencia de mercancías (operaciones virtuales), con pedimento único',
        'UP': 'Unidades prototipo',
        'VC': 'Importación definitiva de vehículos usados en el Estado de Chihuahua',
        'VF': 'Importación definitiva de vehículos usados a la franja o región fronteriza norte',
        'VJ': 'Fronterización de vehículos',
        'VN': 'Importación definitiva de vehículos nuevos',
        'VU': 'Importación definitiva de vehículos usados',
        'V1': 'Transferencias de mercancías',
        'V2': 'Transferencia de mercancías importadas con cuenta aduanera',
        'V3': 'Extracción de depósito fiscal de bienes para su retorno o exportación virtual (IA)',
        'V4': 'Retorno virtual derivado de la constancia de transferencia de mercancías',
        'V5': 'Transferencias de mercancías de empresas certificadas a residentes territorio nacional para su importación definitiva',
        'V6': 'Transferencias de mercancías sujetas a cupo',
        'V7': 'Transferencias del sector azucarero',
        'V8': 'Transferencias de mercancías extranjeras, nacionales y nacionalizadas de tiendas libres de impuestos (Duty Free)',
        'V9': 'Transferencias de mercancías por donación',
        'XL': 'Presentación de mercancía en transporte sobredimensionado',
        'XV': 'Exportación de vehículos de la industria automotriz terminal o manufacturera de vehículos de autotransporte'
    };

    function buildIdentClaveSelect(name) {
        const sel = document.createElement('select');
        sel.name = name; sel.className = 'form-select form-select-sm border-0 ident-clave'; sel.style.fontSize = '.78rem';
        const o0 = new Option('— Seleccionar —',''); o0.disabled=o0.hidden=o0.selected=true; sel.append(o0);
        Object.entries(AP8_keys).forEach(([v, l]) => sel.append(new Option(`${v} – ${l}`, v)));
        return sel;
    }

    function buildTextInput(name) {
        const inp = document.createElement('input');
        inp.type='text'; inp.name=name; inp.className='form-control form-control-sm fc border-0'; inp.maxLength=100;
        return inp;
    }

    function attachIdentRow(tr) {
        tr.querySelector('.btn-remove-ident')?.addEventListener('click', () => {
            tr.remove();
            reindexRows('identsTbody','ident-fila',['ident-clave','ident-c1','ident-c2','ident-c3'],
                ['clave','complemento1','complemento2','complemento3'],'identificadores');
        });
    }

    document.getElementById('btnAddIdent')?.addEventListener('click', () => {
        const idx = document.querySelectorAll('#identsTbody .ident-fila').length;
        const tr  = document.createElement('tr'); tr.className = 'ident-fila';

        const tc1 = document.createElement('td'); tc1.append(buildIdentClaveSelect(`identificadores[${idx}][clave]`));

        const fields = ['complemento1','complemento2','complemento3'];
        const cls    = ['ident-c1','ident-c2','ident-c3'];
        const tds    = fields.map((f, j) => {
            const td = document.createElement('td');
            const inp = buildTextInput(`identificadores[${idx}][${f}]`);
            inp.classList.add(cls[j]);
            td.append(inp); return td;
        });

        const tcBtn = document.createElement('td'); tcBtn.className='text-center align-middle';
        tcBtn.innerHTML=`<button type="button" class="btn btn-sm btn-outline-danger btn-remove-ident px-1 py-0"><i class="bi bi-trash" style="font-size:.7rem"></i></button>`;

        tr.append(tc1, ...tds, tcBtn);
        document.getElementById('identsTbody').append(tr);
        attachIdentRow(tr);
    });

    document.querySelectorAll('#identsTbody .ident-fila').forEach(attachIdentRow);

    // ── 7. Tasas — filas dinámicas ───────────────────────────────
    const AP12c  = ['DTA','C.C.','IVA','ISAN','IGI/IGE','REC.','OTROS','MULT.','2.5','RT',
        'PRV','EUR','REU','MT','IEPS','IVA/PRV','2IB','2IA2','2IA1','2IC','2IF','2IG','2IJ',
        '2II','ICF','IEPSDIE','ICNF','LIEPS','DFC'];
    const AP18c  = ['1','2','3','4','5','6','7','8','9','10'];

    function buildTasaContribSel(name) {
        const sel = document.createElement('select');
        sel.name=name; sel.className='form-select form-select-sm border-0 tasa-contrib'; sel.style.fontSize='.78rem';
        const o0=new Option('— —',''); o0.disabled=o0.hidden=o0.selected=true; sel.append(o0);
        AP12c.forEach(v => sel.append(new Option(v,v)));
        return sel;
    }

    function buildTasaTipoSel(name) {
        const sel = document.createElement('select');
        sel.name=name; sel.className='form-select form-select-sm border-0 tasa-tipo'; sel.style.fontSize='.78rem';
        const o0=new Option('— —',''); o0.disabled=o0.hidden=o0.selected=true; sel.append(o0);
        AP18c.forEach(v => sel.append(new Option(v,v)));
        return sel;
    }

    function attachTasaRow(tr) {
        tr.querySelector('.btn-remove-tasa')?.addEventListener('click', () => {
            tr.remove();
            reindexRows('tasasTbody','tasa-fila',['tasa-contrib','tasa-tipo','tasa-val'],
                ['contribucion','cve_tipo_tasa','tasa'],'tasas');
        });
    }

    document.getElementById('btnAddTasa')?.addEventListener('click', () => {
        const idx = document.querySelectorAll('#tasasTbody .tasa-fila').length;
        const tr  = document.createElement('tr'); tr.className='tasa-fila';

        const tc1=document.createElement('td'); tc1.append(buildTasaContribSel(`tasas[${idx}][contribucion]`));
        const tc2=document.createElement('td'); tc2.append(buildTasaTipoSel(`tasas[${idx}][cve_tipo_tasa]`));

        const tc3=document.createElement('td');
        const inp=document.createElement('input');
        inp.type='number'; inp.name=`tasas[${idx}][tasa]`; inp.className='form-control form-control-sm fc border-0 tasa-val';
        inp.value='0'; inp.step='0.00001'; inp.min='0';
        tc3.append(inp);

        const tc4=document.createElement('td'); tc4.className='text-center align-middle';
        tc4.innerHTML=`<button type="button" class="btn btn-sm btn-outline-danger btn-remove-tasa px-1 py-0"><i class="bi bi-trash" style="font-size:.7rem"></i></button>`;

        tr.append(tc1,tc2,tc3,tc4);
        document.getElementById('tasasTbody').append(tr);
        attachTasaRow(tr);
    });

    document.querySelectorAll('#tasasTbody .tasa-fila').forEach(attachTasaRow);

    // ── Listeners generales ──────────────────────────────────────
    ['valMonedaFact','factorMoneda'].forEach(id =>
        document.getElementById(id)?.addEventListener('input', calcValDolaresFact));
    document.getElementById('monedaFactura')?.addEventListener('change', calcValDolaresFact);
    document.getElementById('tipoCambio')?.addEventListener('input', calcValores);
    document.querySelectorAll('.incr-field,.decr-field').forEach(el => el.addEventListener('input', calcValores));


    // ── 8. PARTIDAS — lógica dinámica ────────────────────────────

    // Catálogos para JS
    const AP12P_keys = ['C.C.','IVA','ISAN','IGI/IGE','2.5','EUR','MT','IEPS','2IB','2IA2','2IA1','2IC','2IF','2IG','2IJ','2II','ICF','IEPSDIE','ICNF','LIEPS'];
    const AP8P_keys  = {
        'AL': 'Mercancía originaria importada al amparo de ALADI',
        'AR': 'Consulta arancelaria',
        'B2': 'Bienes del artículo 2 de la Ley del IEPS',
        'CD': 'Certificado con dispensa temporal',
        'CE': 'Certificado de elegibilidad',
        'CF': 'Preferencia arancelaria para empresas ubicadas en la franja o región fronteriza',
        'DC': 'Clasificación del cupo',
        'DH': 'Datos de importación de hidrocarburos',
        'DP': 'Introducción y extracción de depósito fiscal para exposición y venta de artículos promocionales',
        'DR': 'Rectificación por discrepancia documental',
        'DS': 'Destrucción de mercancías en depósito fiscal para la exposición y venta',
        'DT': 'Operaciones sujetas al artículo 2.5 del T-MEC',
        'DU': 'Operaciones sujetas a los arts. 14 del Anexo III de la Decisión, 15 del Anexo I del TLCAELC o al ACC',
        'DV': 'Venta de mercancías a misiones diplomáticas y consulares cuando cuente con franquicia diplomática',
        'EA': 'Excepción de aviso automático de importación/exportación',
        'EB': 'Envases y empaques',
        'EC': 'Excepción de pago de cuota compensatoria',
        'EF': 'Estímulo fiscal',
        'EN': 'No aplicación de la Norma Oficial Mexicana',
        'EO': 'Emisor del certificado de origen',
        'EP': 'Excepción de inscripción al padrón de importadores',
        'ES': 'Estado de la mercancía',
        'EX': 'Exención de cuenta aduanera de garantía',
        'FC': 'Fracción correlacionada',
        'GA': 'Cuenta aduanera de garantía',
        'GI': 'Garantía IMMEX',
        'HI': 'Tipo de gasolina',
        'IA': 'Certificado de aprobación para producción de partes aeronáuticas',
        'IF': 'Preferencia arancelaria para empresas ubicadas en la región fronteriza de Chetumal',
        'II': 'Inventario inicial de empresas denominadas Duty Free',
        'IN': 'Incidencia',
        'IS': 'Mercancías exentas de impuestos al comercio exterior',
        'LP': 'Lista de escaso abasto',
        'MA': 'Embalajes de madera',
        'MB': 'Marbetes y/o precintos',
        'MC': 'Marca',
        'ME': 'Material de ensamble',
        'MM': 'Importación definitiva de muestras y muestrarios',
        'MR': 'Registro para la toma de muestras, peligrosas o para las que se requiera de instalaciones o equipos especiales para la toma de las mismas',
        'MV': 'Año-modelo del vehículo',
        'NA': 'Mercancías con preferencia arancelaria ALADI señaladas en el Acuerdo',
        'NE': 'Excepción de cumplir con el Anexo 21',
        'NS': 'Excepción de inscripción en los padrones de importadores y exportadores-sectoriales',
        'NT': 'Nota de Tratado',
        'NZ': 'Mercancía que no se ha beneficiado del "Sugar Re-Export Program" de los Estados Unidos de América',
        'OM': 'Mercancía originaria de México',
        'OV': 'Operación vulnerable',
        'PA': 'Cumplimiento de la Norma Oficial Mexicana, para verificarse en un almacén general de depósito autorizado',
        'PB': 'Cumplimiento de NOM para su verificación dentro del territorio nacional, en un domicilio particular',
        'PG': 'Mercancía peligrosa',
        'PM': 'Presentación de la mercancía',
        'PO': 'Proveedor de origen',
        'PR': 'Proporción determinada',
        'PS': 'Sector autorizado al amparo de PROSEC',
        'PT': 'Exportación o retorno de producto terminado',
        'PV': 'Prueba de valor',
        'RA': 'Retorno de racks',
        'RF': 'Cuota compensatoria basada en precios de referencia',
        'RP': 'Retorno de residuos peligrosos generados por empresas con programa IMMEX',
        'SB': 'Importación de organismos genéticamente modificados',
        'SC': 'Excepción de pago de medida de transición',
        'SH': 'Autorización del SAT',
        'SM': 'Excepción de la declaración de marbetes',
        'TA': 'Régimen de transición alternativo',
        'TB': 'Tránsito interno por aduanas y mercancías específicas',
        'TC': 'Correlación de las fracciones arancelarias',
        'TL': 'Mercancía originaria al amparo de Tratados de Libre Comercio',
        'TV': 'Total de mercancía extraída de depósito fiscal',
        'UM': 'Uso de la mercancía',
        'VT': 'Importación de autobuses, camiones y tractocamiones usados para el transporte de personas y mercancías',
        'XP': 'Excepción al cumplimiento de regulaciones y restricciones no arancelarias',
        'ZC': 'Contenido de azúcar'
    };

    // ── Calcular Imp. Precio Pag y contribuciones de partidas ────
    function calcPartidaValores() {
        const bloques = document.querySelectorAll('.partida-bloque');
        if (!bloques.length) return;

        const tc = num('tipoCambio') || 0;

        // Primero calcular Imp. Precio Pag de cada partida = Val. Adu/USD × Tipo de Cambio
        bloques.forEach(b => {
            const valAdu = parseFloat(b.querySelector('.partida-val-adu')?.value) || 0;
            const impPag = b.querySelector('.partida-imp-pag');
            if (impPag) impPag.value = Math.round(valAdu * tc * 100) / 100;
        });

        // Suma total de Imp. Precio Pag de todas las partidas
        let sumaPreciosPag = 0;
        bloques.forEach(b => {
            sumaPreciosPag += parseFloat(b.querySelector('.partida-imp-pag')?.value) || 0;
        });

        const valorAduanaTotal = num('valorAduana') || 0;
        const factorProrrateo = sumaPreciosPag > 0 ? valorAduanaTotal / sumaPreciosPag : 0;

        bloques.forEach(b => {
            const impPag = parseFloat(b.querySelector('.partida-imp-pag')?.value) || 0;
            calcContribsPartida(b, impPag, factorProrrateo, valorAduanaTotal);
        });
    }

    // Importe contribución = base × tasa según tipo (Campo 25)
    function calcContribsPartida(bloque, impPag, factorProrrateo, valorAduanaTotal) {
        bloque.querySelectorAll('.contrib-partida-fila').forEach(tr => {
            const con     = tr.querySelector('.cp-con')?.value;
            const tasa    = parseFloat(tr.querySelector('.cp-tasa')?.value) || 0;
            const tt      = tr.querySelector('.cp-tt')?.value;
            const importe = tr.querySelector('.cp-importe');
            if (!importe) return;

            let base = impPag * factorProrrateo; // base = val aduana partida
            let resultado = 0;

            if (tt === '1') {
                // Porcentual: base × tasa / 100
                resultado = base * tasa / 100;
            } else if (tt === '7') {
                // Al millar (DTA): base × tasa / 1000
                resultado = base * tasa / 1000;
            } else if (tt === '3') {
                // Cuota mínima DTA
                resultado = tasa;
            } else if (tt === '4') {
                // Cuota fija
                resultado = tasa;
            } else if (tt === '2' || tt === '9' || tt === '10') {
                // Específico: tasa × cantidad
                resultado = tasa;
            } else {
                resultado = base * tasa / 100;
            }
            importe.value = Math.round(resultado * 100) / 100;
        });
    }

    // ── Clonar bloque de partida ─────────────────────────────────
    function getPartidaTemplate(idx) {
        const sec = String(idx + 1).padStart(3, '0');

        const ap7   = {'1':'1 - Kilo','2':'2 - Gramo','3':'3 - Metro lineal','4':'4 - Metro cuadrado','5':'5 - Metro cubico','6':'6 - Pieza','7':'7 - Cabeza','8':'8 - Litro','9':'9 - Par','10':'10 - Kilowatt','11':'11 - Millar','12':'12 - Juego','13':'13 - Kilowatt/Hora','14':'14 - Tonelada','15':'15 - Barril','16':'16 - Gramo neto','17':'17 - Decenas','18':'18 - Cientos','19':'19 - Docenas','20':'20 - Caja','21':'21 - Botella','22':'22 - Carat'};
        const ap11  = {'0':'0 - Valor comercial (exp.)','1':'1 - Valor de transaccion','2':'2 - Mercancias identicas','3':'3 - Mercancias similares','4':'4 - Precio unitario de venta','5':'5 - Valor reconstruido','6':'6 - Ultimo recurso'};
        const ap4   = {'A4':'A4 - DEU - Alemania','B4':'B4 - ARG - Argentina','B5':'B5 - AUS - Australia','C2':'C2 - BEL - Belgica','C8':'C8 - BRA - Brasil','D9':'D9 - CAN - Canada','F6':'F6 - CHL - Chile','Z3':'Z3 - CHN - China','E4':'E4 - COL - Colombia','F2':'F2 - CRI - Costa Rica','G7':'G7 - ESP - Espana','G8':'G8 - USA - Estados Unidos','H5':'H5 - FRA - Francia','I6':'I6 - GTM - Guatemala','J5':'J5 - HND - Honduras','J6':'J6 - HKG - Hong Kong','J8':'J8 - IND - India','J9':'J9 - IDN - Indonesia','K6':'K6 - ITA - Italia','K9':'K9 - JPN - Japon','E8':'E8 - KOR - Corea del Sur','N3':'N3 - MEX - Mexico','J4':'J4 - NLD - Paises Bajos','Q8':'Q8 - PAN - Panama','R2':'R2 - PER - Peru','R5':'R5 - POL - Polonia','R6':'R6 - PRT - Portugal','R9':'R9 - GBR - Reino Unido','RU':'RU - RUS - Rusia','U8':'U8 - CHE - Suiza','F7':'F7 - TWN - Taiwan','V1':'V1 - THA - Tailandia','W4':'W4 - TUR - Turquia','W9':'W9 - VNM - Vietnam','Z9':'Z9 - KCD - No declarado'};
        const ap12p = ['C.C.','IVA','ISAN','IGI/IGE','2.5','EUR','MT','IEPS','2IB','2IA2','2IA1','2IC','2IF','2IG','2IJ','2II','ICF','IEPSDIE','ICNF','LIEPS'];
        const ap18l = ['1','2','3','4','5','6','7','8','9','10'];
        const ap13l = ['0','2','4','5','6','7','8','9','12','13','14','15','16','18','19','21','22'];
        const ap8pl = AP8P_keys;

        const mkOpts = (obj, ph) => {
            let h = '<option value="" disabled hidden selected>' + ph + '</option>';
            if (Array.isArray(obj)) {
                obj.forEach(v => { h += '<option value="' + v + '">' + v + '</option>'; });
            } else {
                Object.entries(obj).forEach(([v, l]) => { h += '<option value="' + v + '">' + l + '</option>'; });
            }
            return h;
        };

        const div = document.createElement('div');
        div.className = 'partida-bloque';
        div.setAttribute('data-pi', idx);
        div.style.borderBottom = '2px solid #c8d8ed';

        div.innerHTML =
            '<div class="d-flex justify-content-between align-items-center px-3 py-1" style="background:#e8f0f8;border-bottom:1px solid #c8d8ed">' +
                '<span style="font-size:.72rem;font-weight:700;color:var(--adu-blue);text-transform:uppercase;letter-spacing:.05em">' +
                    '<i class="bi bi-tag me-1"></i> Partida <span class="partida-sec-label">' + sec + '</span>' +
                '</span>' +
                '<button type="button" class="btn btn-sm btn-outline-danger btn-remove-partida py-0 px-2" style="font-size:.68rem">' +
                    '<i class="bi bi-trash me-1"></i> Eliminar partida' +
                '</button>' +
            '</div>' +
            '<div class="sec-body">' +

                // Fila 1: SEC, Fraccion, SUBD, VINC, Metodo Val
                '<div class="row g-2 mb-2">' +
                    '<div class="col-md-1">' +
                        '<label class="form-label label-auto">SEC. <span class="badge-auto">Auto</span></label>' +
                        '<input type="text" name="partidas[' + idx + '][sec]" class="form-control fc campo-auto text-center" value="' + sec + '" readonly>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">Fraccion <span class="text-danger">*</span></label>' +
                        '<input type="text" name="partidas[' + idx + '][fraccion]" class="form-control fc" maxlength="8" placeholder="00000000">' +
                        '<div class="form-text">TIGIE - 8 digitos</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">SUBD / Num. Ident. Comercial</label>' +
                        '<input type="text" name="partidas[' + idx + '][subd_nico]" class="form-control fc" maxlength="20">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">VINC.</label>' +
                        '<select name="partidas[' + idx + '][vinc]" class="form-select" style="font-size:.82rem">' +
                            '<option value="" disabled hidden selected>--</option>' +
                            '<option value="0">0 - Sin vinculacion</option>' +
                            '<option value="1">1 - Si, no afecta valor</option>' +
                            '<option value="2">2 - Si, afecta valor</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<label class="form-label">Metodo Valoracion <span class="text-danger">*</span></label>' +
                        '<select name="partidas[' + idx + '][met_val]" class="form-select" style="font-size:.82rem">' + mkOpts(ap11,'-- Apendice 11 --') + '</select>' +
                        '<div class="form-text">Apendice 11</div>' +
                    '</div>' +
                '</div>' +

                // Fila 2: UMC, Cant UMC, UMT, Cant UMT, P.V/C, P.O/D
                '<div class="row g-2 mb-2">' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">UMC <span class="text-danger">*</span></label>' +
                        '<select name="partidas[' + idx + '][umc]" class="form-select" style="font-size:.82rem">' + mkOpts(ap7,'-- Apendice 7 --') + '</select>' +
                        '<div class="form-text">Apendice 7</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">Cantidad UMC <span class="text-danger">*</span></label>' +
                        '<input type="number" name="partidas[' + idx + '][cantidad_umc]" class="form-control fc partida-cant-umc" value="0" step="0.000001" min="0">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">UMT</label>' +
                        '<select name="partidas[' + idx + '][umt]" class="form-select" style="font-size:.82rem">' + mkOpts(ap7,'-- Apendice 7 --') + '</select>' +
                        '<div class="form-text">Apendice 7</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">Cantidad UMT</label>' +
                        '<input type="number" name="partidas[' + idx + '][cantidad_umt]" class="form-control fc" value="0" step="0.000001" min="0">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">P. V/C</label>' +
                        '<select name="partidas[' + idx + '][p_vc]" class="form-select" style="font-size:.82rem">' + mkOpts(ap4,'-- Pais --') + '</select>' +
                        '<div class="form-text">Apendice 4</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">P. O/D</label>' +
                        '<select name="partidas[' + idx + '][p_od]" class="form-select" style="font-size:.82rem">' + mkOpts(ap4,'-- Pais --') + '</select>' +
                        '<div class="form-text">Apendice 4</div>' +
                    '</div>' +
                '</div>' +

                // Descripcion
                '<div class="row g-2 mb-2">' +
                    '<div class="col-12">' +
                        '<label class="form-label">Descripcion de la Mercancia <span class="text-danger">*</span></label>' +
                        '<textarea name="partidas[' + idx + '][descripcion]" class="form-control fc" rows="2" placeholder="Naturaleza y caracteristicas tecnicas y comerciales necesarias para la clasificacion arancelaria..." required></textarea>' +
                    '</div>' +
                '</div>' +

                // Valores
                '<div class="row g-2 mb-2">' +
                    '<div class="col-md-3">' +
                        '<label class="form-label label-auto">Imp. Precio Pag. / Val. Comercial <span class="badge-auto">Auto</span></label>' +
                        '<div class="input-group input-group-sm"><span class="input-group-text">$</span>' +
                        '<input type="number" name="partidas[' + idx + '][imp_precio_pag]" class="form-control fc campo-auto partida-imp-pag" value="0" step="0.01" min="0" readonly title="Val. Adu/USD × Tipo de Cambio"></div>' +
                        '<div class="form-text">Campo 14 = Val. USD × T.C.</div>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<label class="form-label">Val. Adu / Val. USD <span class="text-danger">*</span></label>' +
                        '<div class="input-group input-group-sm"><span class="input-group-text">$</span>' +
                        '<input type="number" name="partidas[' + idx + '][val_adu_usd]" class="form-control fc partida-val-adu" value="0" step="0.01" min="0"></div>' +
                        '<div class="form-text">Valor en aduana / USD por partida</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">Precio Unit. <span class="text-danger">*</span></label>' +
                        '<div class="input-group input-group-sm"><span class="input-group-text">$</span>' +
                        '<input type="number" name="partidas[' + idx + '][precio_unit]" class="form-control fc partida-precio-unit" value="0" step="0.000001" min="0" title="Precio pag. ÷ Cant. UMC"></div>' +
                        '<div class="form-text">Precio pag. ÷ Cant. UMC</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label class="form-label">Val. Agregado</label>' +
                        '<div class="input-group input-group-sm"><span class="input-group-text">$</span>' +
                        '<input type="number" name="partidas[' + idx + '][val_agreg]" class="form-control fc" value="0" step="0.01" min="0"></div>' +
                        '<div class="form-text">Solo IMMEX/maquila</div>' +
                    '</div>' +
                '</div>' +

                // Marca, Modelo, Codigo
                '<div class="row g-2 mb-2">' +
                    '<div class="col-md-4"><label class="form-label">Marca</label><input type="text" name="partidas[' + idx + '][marca]" class="form-control fc" maxlength="100"></div>' +
                    '<div class="col-md-4"><label class="form-label">Modelo</label><input type="text" name="partidas[' + idx + '][modelo]" class="form-control fc" maxlength="100"></div>' +
                    '<div class="col-md-4"><label class="form-label">Codigo Producto</label><input type="text" name="partidas[' + idx + '][codigo_producto]" class="form-control fc" maxlength="50"></div>' +
                '</div>' +

                // Contribuciones nivel partida
                '<div class="mb-2 p-2 rounded" style="background:#f8fafc;border:1px solid #e2eaf4">' +
                    '<div class="d-flex justify-content-between align-items-center mb-1">' +
                        '<span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--adu-blue);letter-spacing:.04em">' +
                            '<i class="bi bi-percent me-1"></i> Contribuciones a Nivel Partida' +
                            '<span style="font-size:.6rem;color:#64748b;font-weight:400"> CON . TASA . T.T. . F.P. . IMPORTE (Campos 21-25)</span>' +
                        '</span>' +
                        '<button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-add-contrib-partida" style="font-size:.68rem" data-pi="' + idx + '">' +
                            '<i class="bi bi-plus-lg me-1"></i> Agregar' +
                        '</button>' +
                    '</div>' +
                    '<div class="table-responsive">' +
                        '<table class="table table-sm table-bordered mb-0" style="font-size:.75rem">' +
                            '<thead style="background:var(--adu-dark);color:#fff">' +
                                '<tr>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45">CON. (Ap.12)</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45;width:100px">Tasa</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45;width:120px">T.T. (Ap.18)</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45;width:90px">F.P. (Ap.13)</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45;width:120px">Importe ($)</th>' +
                                    '<th style="width:36px;border-color:#1e2d45"></th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody class="contrib-partida-tbody" data-pi="' + idx + '">' +
                                '<tr class="contrib-partida-fila">' +
                                    '<td><select name="partidas[' + idx + '][contribs][0][con]" class="form-select form-select-sm border-0 cp-con" style="font-size:.75rem">' + mkOpts(ap12p,'--') + '</select></td>' +
                                    '<td><input type="number" name="partidas[' + idx + '][contribs][0][tasa]" class="form-control form-control-sm fc border-0 cp-tasa" value="0" step="0.00001" min="0"></td>' +
                                    '<td><select name="partidas[' + idx + '][contribs][0][tt]" class="form-select form-select-sm border-0 cp-tt" style="font-size:.75rem">' + mkOpts(ap18l,'--') + '</select></td>' +
                                    '<td><select name="partidas[' + idx + '][contribs][0][fp]" class="form-select form-select-sm border-0 cp-fp" style="font-size:.75rem">' + mkOpts(ap13l,'--') + '</select></td>' +
                                    '<td><input type="number" name="partidas[' + idx + '][contribs][0][importe]" class="form-control form-control-sm fc border-0 campo-auto cp-importe" value="0" step="0.01" min="0" readonly></td>' +
                                    '<td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-contrib-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button></td>' +
                                '</tr>' +
                            '</tbody>' +
                        '</table>' +
                    '</div>' +
                '</div>' +

                // Identificadores nivel partida
                '<div class="mb-2 p-2 rounded" style="background:#f8fafc;border:1px solid #e2eaf4">' +
                    '<div class="d-flex justify-content-between align-items-center mb-1">' +
                        '<span style="font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--adu-blue);letter-spacing:.04em">' +
                            '<i class="bi bi-key me-1"></i> Identificadores a Nivel Partida' +
                            '<span style="font-size:.6rem;color:#64748b;font-weight:400"> (Apendice 8 - Nivel P)</span>' +
                        '</span>' +
                        '<button type="button" class="btn btn-sm btn-outline-primary py-0 px-2 btn-add-ident-partida" style="font-size:.68rem" data-pi="' + idx + '">' +
                            '<i class="bi bi-plus-lg me-1"></i> Agregar' +
                        '</button>' +
                    '</div>' +
                    '<div class="table-responsive">' +
                        '<table class="table table-sm table-bordered mb-0" style="font-size:.75rem">' +
                            '<thead style="background:var(--adu-dark);color:#fff">' +
                                '<tr>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45;width:240px">IDENTIF. (Apendice 8 - P)</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45">Complemento 1</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45">Complemento 2</th>' +
                                    '<th style="font-size:.65rem;border-color:#1e2d45">Complemento 3</th>' +
                                    '<th style="width:36px;border-color:#1e2d45"></th>' +
                                '</tr>' +
                            '</thead>' +
                            '<tbody class="ident-partida-tbody" data-pi="' + idx + '"></tbody>' +
                        '</table>' +
                    '</div>' +
                '</div>' +

                // Observaciones nivel partida
                '<div class="row g-2">' +
                    '<div class="col-12">' +
                        '<label class="form-label">Observaciones a Nivel Partida</label>' +
                        '<textarea name="partidas[' + idx + '][obs_partida]" class="form-control fc" rows="2" placeholder="Datos adicionales a nivel partida..."></textarea>' +
                    '</div>' +
                '</div>' +

            '</div>';

        return div;
    }

    function reindexPartidas() {
        document.querySelectorAll('.partida-bloque').forEach((b, i) => {
            b.setAttribute('data-pi', i);
            b.querySelector('.partida-sec-label').textContent = String(i+1).padStart(3,'0');
            b.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/partidas\[\d+\]/g, `partidas[${i}]`);
            });
            b.querySelectorAll('[data-pi]').forEach(el => el.setAttribute('data-pi', i));
        });
    }

    function attachPartidaListeners(bloque) {
        // Recalcular al cambiar val. adu/usd o cantidad UMC
        bloque.querySelector('.partida-val-adu')?.addEventListener('input', calcPartidaValores);
        bloque.querySelector('.partida-cant-umc')?.addEventListener('input', calcPartidaValores);

        // Recalcular al cambiar tasa o tipo de tasa en contribuciones
        bloque.querySelectorAll('.cp-tasa,.cp-tt').forEach(el =>
            el.addEventListener('input', calcPartidaValores)
        );

        // Eliminar partida
        bloque.querySelector('.btn-remove-partida')?.addEventListener('click', () => {
            bloque.remove();
            reindexPartidas();
            calcPartidaValores();
            if (!document.querySelectorAll('.partida-bloque').length) {
                document.getElementById('partidasVacias')?.style.removeProperty('display');
            }
        });

        // Agregar contribución en esta partida
        bloque.querySelector('.btn-add-contrib-partida')?.addEventListener('click', () => {
            const pi   = bloque.getAttribute('data-pi');
            const tbody = bloque.querySelector('.contrib-partida-tbody');
            const ci   = tbody.querySelectorAll('.contrib-partida-fila').length;
            const tr   = document.createElement('tr'); tr.className = 'contrib-partida-fila';

            const selCon = buildPartidaSelect(`partidas[${pi}][contribs][${ci}][con]`, AP12P_keys, 'cp-con');
            const inpTasa = buildPartidaNumInput(`partidas[${pi}][contribs][${ci}][tasa]`, 'cp-tasa');
            const selTT  = buildPartidaSelect(`partidas[${pi}][contribs][${ci}][tt]`, Object.keys(AP18c), 'cp-tt');
            const selFP  = buildPartidaSelect(`partidas[${pi}][contribs][${ci}][fp]`, Object.keys(AP13), 'cp-fp');
            const inpImp = buildPartidaNumInput(`partidas[${pi}][contribs][${ci}][importe]`, 'cp-importe campo-auto');
            inpImp.readOnly = true;

            [selCon,inpTasa,selTT,selFP,inpImp].forEach((el,k) => {
                const td = document.createElement('td'); td.append(el); tr.append(td);
            });
            const tdBtn = document.createElement('td'); tdBtn.className='text-center align-middle';
            tdBtn.innerHTML=`<button type="button" class="btn btn-sm btn-outline-danger btn-remove-contrib-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button>`;
            tr.append(tdBtn);
            tbody.append(tr);
            attachContribRemove(tr);
            [inpTasa, selTT].forEach(el => el.addEventListener('input', calcPartidaValores));
            selTT.addEventListener('change', calcPartidaValores);
        });

        // Agregar identificador en esta partida
        bloque.querySelector('.btn-add-ident-partida')?.addEventListener('click', () => {
            const pi    = bloque.getAttribute('data-pi');
            const tbody = bloque.querySelector('.ident-partida-tbody');
            const ii    = tbody.querySelectorAll('.ident-partida-fila').length;
            const tr    = document.createElement('tr'); tr.className = 'ident-partida-fila';

            const selClave = document.createElement('select');
            selClave.name = `partidas[${pi}][idents][${ii}][clave]`;
            selClave.className = 'form-select form-select-sm border-0 ip-clave';
            selClave.style.fontSize = '.75rem';
            const o0 = new Option('— —',''); o0.disabled=o0.hidden=o0.selected=true; selClave.append(o0);
            Object.entries(AP8P_keys).forEach(([v,l]) => selClave.append(new Option(`${v} – ${l}`, v)));

            const td1 = document.createElement('td'); td1.append(selClave);
            ['c1','c2','c3'].forEach(f => {
                const td = document.createElement('td');
                const inp = document.createElement('input');
                inp.type='text'; inp.name=`partidas[${pi}][idents][${ii}][${f}]`;
                inp.className=`form-control form-control-sm fc border-0 ip-${f}`; inp.maxLength=100;
                td.append(inp); tr.append(td);
            });
            const tdBtn = document.createElement('td'); tdBtn.className='text-center align-middle';
            tdBtn.innerHTML=`<button type="button" class="btn btn-sm btn-outline-danger btn-remove-ident-partida px-1 py-0"><i class="bi bi-trash" style="font-size:.65rem"></i></button>`;
            tr.prepend(td1); tr.append(tdBtn);
            tbody.append(tr);
            attachIdentPartidaRow(tr);
        });

        // Listeners eliminar contribución/identificador existentes
        bloque.querySelectorAll('.btn-remove-contrib-partida').forEach(attachContribRemove);
        bloque.querySelectorAll('.ident-partida-fila').forEach(attachIdentPartidaRow);
    }

    // ── Reglas Apéndice 8 Anexo 22 — Identificadores a Nivel Partida (P) ──
    const RULES_IDENT_PARTIDA = {
        'DP': { c1: { enabled: true, ph: 'Identificar artículos promocionales conforme a la regla 4.5.27' }, c2: { enabled: false }, c3: { enabled: false } },
        'DR': { c1: { enabled: true, ph: 'Rectificar datos asentados conforme a la regla 4.5.7' }, c2: { enabled: false }, c3: { enabled: false } },
        'DS': { c1: { enabled: true, ph: 'Número de acta de hechos' }, c2: { enabled: false }, c3: { enabled: false } },
        'DT': { c1: { enabled: true, ph: 'Clave del supuesto aplicable conforme al catálogo del Apéndice 8' }, c2: { enabled: false }, c3: { enabled: false } },
        'EP': { c1: { enabled: true, ph: 'Clave de excepción conforme a la regla 1.3.1' }, c2: { enabled: false }, c3: { enabled: false } },
        'ES': {
            c1: { enabled: true, ph: 'N = Nuevos; U = Usados; R = Reconstruidos; RM = Remanufacturados', options: ['N', 'U', 'R', 'RM'] },
            c2: { enabled: true, ph: 'Si se declara RM, indicar tratado: T-MEC o TIP', options: ['T-MEC', 'TIP'] },
            c3: { enabled: false }
        },
        'EX': { c1: { enabled: true, ph: 'Clave del supuesto de excepción (regla 1.6.29 y Apéndice 8)' }, c2: { enabled: false }, c3: { enabled: false } },
        'FC': {
            c1: { enabled: true, ph: '1 = Fracción tarifa anterior; 2 = Nueva tarifa', options: ['1', '2'] },
            c2: { enabled: true, ph: 'Fracción arancelaria correlacionada' },
            c3: { enabled: true, ph: 'NICO que corresponda' }
        },
        'IF': { c1: { enabled: false }, c2: { enabled: false }, c3: { enabled: false } },
        'II': { c1: { enabled: false }, c2: { enabled: false }, c3: { enabled: false } },
        'IN': {
            c1: { enabled: true, ph: 'Clave del supuesto (1, 2, 4, 5, 6, 11, 14...)' },
            c2: { enabled: true, ph: 'Número de acta (SIRESI o 1er reconocimiento)' },
            c3: { enabled: true, ph: '1 = Primer reconoc.; 2 = No aplica; 3 = Toma de muestras; 4 = Verific. en transporte', options: ['1', '2', '3', '4'] }
        },
        'IS': { c1: { enabled: true, ph: 'Fracción del Art. 61 de la Ley que aplica' }, c2: { enabled: false }, c3: { enabled: false } },
        'LP': {
            c1: { enabled: true, ph: '1er material de escaso abasto (Ap. 1 Anexo 4-A TIPAT)' },
            c2: { enabled: true, ph: '2do material de escaso abasto (si existe)' },
            c3: { enabled: true, ph: '3er material de escaso abasto (si existe)' }
        },
        'ME': {
            c1: { enabled: true, ph: 'Fracción y NICO: 9803.00.01 00 o 9803.00.02 00', options: ['9803.00.01 00', '9803.00.02 00'] },
            c2: { enabled: false },
            c3: { enabled: false }
        },
        'MM': {
            c1: { enabled: true, ph: '1 = Juguetes; 2 = Otros', options: ['1', '2'] },
            c2: { enabled: false },
            c3: { enabled: false }
        },
        'MR': { c1: { enabled: true, ph: 'Número de oficio de autorización emitido por la ANAM' }, c2: { enabled: false }, c3: { enabled: false } },
        'MV': {
            c1: { enabled: true, ph: 'Año-modelo a 4 dígitos' },
            c2: { enabled: true, ph: 'Número correspondiente catálogo de precios estimados' },
            c3: { enabled: true, ph: 'Número de registro empresa proveedora vehículos usados' }
        },
        'NA': {
            c1: { enabled: true, ph: 'Clave del acuerdo o supuesto de preferencia (Apéndice 8)' },
            c2: { enabled: true, ph: 'Constancia de exportación Secretaría de Economía' },
            c3: { enabled: true, ph: 'País de origen de la mercancía' }
        },
        'NT': {
            c1: { enabled: true, ph: 'Clave del país Parte del Tratado (Apéndice 4)' },
            c2: { enabled: true, ph: 'Clave de la nota apéndice decreto / número de artículo' },
            c3: { enabled: false }
        },
        'NZ': { c1: { enabled: false }, c2: { enabled: false }, c3: { enabled: false } },
        'TB': {
            c1: { enabled: true, ph: '1=Confecciones; 2=Calzado; 3=Electrodomésticos; 4=Juguetes; 5=LIEPS; 6=Electrónicos; 7=Textiles; 8=Llantas; 9=Plaguicidas', options: ['1', '2', '3', '4', '5', '6', '7', '8', '9'] },
            c2: { enabled: false },
            c3: { enabled: false }
        },
        'TC': { c1: { enabled: true, ph: 'Fracción arancelaria indicada con código CORR' }, c2: { enabled: false }, c3: { enabled: false } },
        'TL': {
            c1: { enabled: true, ph: 'Clave del país/grupo exportador (Apéndice 4)' },
            c2: { enabled: true, ph: 'Clave o código del Tratado (Apéndice 8)' },
            c3: { enabled: true, ph: 'Certificado de origen cuando corresponda' }
        },
        'VT': { c1: { enabled: true, ph: 'Clave del tipo/capacidad del vehículo (Apéndice 8)' }, c2: { enabled: false }, c3: { enabled: false } },
        'ZC': { c1: { enabled: true, ph: 'Contenido de azúcar expresado en kilogramos' }, c2: { enabled: false }, c3: { enabled: false } }
    };

    function initDatalistsPartida() {
        if (document.getElementById('datalistsIdentPartida')) return;
        const container = document.createElement('div');
        container.id = 'datalistsIdentPartida';
        container.style.display = 'none';

        Object.entries(RULES_IDENT_PARTIDA).forEach(([clave, rule]) => {
            ['c1', 'c2', 'c3'].forEach(cKey => {
                const cRule = rule[cKey];
                if (cRule && cRule.options && cRule.options.length > 0) {
                    const dl = document.createElement('datalist');
                    dl.id = `list_ip_${clave}_${cKey}`;
                    cRule.options.forEach(optVal => {
                        const opt = document.createElement('option');
                        opt.value = optVal;
                        dl.appendChild(opt);
                    });
                    container.appendChild(dl);
                }
            });
        });
        document.body.appendChild(container);
    }

    function applyIdentPartidaRule(tr) {
        if (!tr) return;
        const selClave = tr.querySelector('.ip-clave');
        if (!selClave) return;

        const clave = selClave.value;
        const rule = RULES_IDENT_PARTIDA[clave];

        ['c1', 'c2', 'c3'].forEach(cKey => {
            const inp = tr.querySelector(`.ip-${cKey}`);
            if (!inp) return;

            const cRule = rule ? rule[cKey] : { enabled: true, ph: '' };

            if (!cRule || !cRule.enabled) {
                inp.value = '';
                inp.placeholder = 'No asentar datos. (Vacío)';
                inp.disabled = true;
                inp.readOnly = true;
                inp.style.backgroundColor = 'rgba(100, 116, 139, 0.12)';
                inp.style.opacity = '0.5';
                inp.style.cursor = 'not-allowed';
                inp.removeAttribute('list');
                inp.removeAttribute('title');
            } else {
                inp.disabled = false;
                inp.readOnly = false;
                inp.style.backgroundColor = '';
                inp.style.opacity = '1';
                inp.style.cursor = '';
                inp.placeholder = cRule.ph || '';

                if (cRule.options && cRule.options.length > 0) {
                    inp.setAttribute('list', `list_ip_${clave}_${cKey}`);
                    inp.title = 'Opciones sugeridas: ' + cRule.options.join(', ');
                } else {
                    inp.removeAttribute('list');
                    inp.removeAttribute('title');
                }
            }
        });
    }

    function attachIdentPartidaRow(tr) {
        if (!tr) return;
        const selClave = tr.querySelector('.ip-clave');
        if (selClave) {
            selClave.addEventListener('change', () => applyIdentPartidaRule(tr));
        }
        applyIdentPartidaRule(tr);
        tr.querySelector('.btn-remove-ident-partida')?.addEventListener('click', () => tr.remove());
    }

    function attachContribRemove(tr) {
        tr.querySelector('.btn-remove-contrib-partida')?.addEventListener('click', () => {
            tr.remove(); calcPartidaValores();
        });
    }

    function buildPartidaSelect(name, keys, cls) {
        const sel = document.createElement('select');
        sel.name=name; sel.className=`form-select form-select-sm border-0 ${cls}`; sel.style.fontSize='.75rem';
        const o0=new Option('— —',''); o0.disabled=o0.hidden=o0.selected=true; sel.append(o0);
        keys.forEach(v => sel.append(new Option(v,v)));
        return sel;
    }

    function buildPartidaNumInput(name, cls) {
        const inp = document.createElement('input');
        inp.type='number'; inp.name=name; inp.className=`form-control form-control-sm fc border-0 ${cls}`;
        inp.value='0'; inp.step='0.01'; inp.min='0';
        return inp;
    }

    // Botón Agregar Partida
    document.getElementById('btnAddPartida')?.addEventListener('click', () => {
        const idx = document.querySelectorAll('.partida-bloque').length;
        const empty = document.getElementById('partidasVacias');
        if (empty) empty.style.display = 'none';

        const tmpl = getPartidaTemplate(idx);
        if (tmpl) {
            document.getElementById('partidasContainer').append(tmpl);
            attachPartidaListeners(tmpl);
        }
    });

    // Inicializar listeners e identificadores a nivel partida existentes
    initDatalistsPartida();
    document.querySelectorAll('.ident-partida-fila').forEach(attachIdentPartidaRow);
    document.querySelectorAll('.partida-bloque').forEach(attachPartidaListeners);

    // Recalcular partidas cuando cambia val aduana (tipo cambio, etc.)
    document.getElementById('tipoCambio')?.addEventListener('input', calcPartidaValores);

    // Cálculo inicial
    calcPartidaValores();

    // ── Formato Automático de Núm. Pedimento ───────────────────
    if (numPedInput) {
        numPedInput.addEventListener('input', function(e) {
            if (e.inputType === 'deleteContentBackward') return;
            let val = this.value.replace(/\D/g, '');
            let res = '';
            if (val.length > 0) res += val.substring(0, 2);
            if (val.length > 2) res += ' ' + val.substring(2, 4);
            if (val.length > 4) res += ' ' + val.substring(4, 8);
            if (val.length > 8) res += ' ' + val.substring(8, 15);
            this.value = res;
        });
    }

    // ── Convertir textos a MAYÚSCULAS automáticamente ────────────
    document.addEventListener('input', function(e) {
        // Solo aplicar a inputs de texto y textareas
        if ((e.target.tagName === 'INPUT' && (e.target.type === 'text' || e.target.type === 'search')) || e.target.tagName === 'TEXTAREA') {
            // Guardar posición del cursor para evitar saltos molestos
            let start = e.target.selectionStart;
            let end = e.target.selectionEnd;
            let upper = e.target.value.toUpperCase();
            
            // Solo reemplazar si hay un cambio real (mejora rendimiento)
            if (e.target.value !== upper) {
                e.target.value = upper;
                // Restaurar cursor si el campo lo soporta
                if (typeof start === 'number') {
                    e.target.setSelectionRange(start, end);
                }
            }
        }
    });

    // ── Session Keep-Alive ───────────────────────────────────────
    // Hace una petición cada 5 minutos para mantener la sesión activa
    setInterval(function() {
        fetch('{{ route("session.keepalive") }}')
            .then(response => response.json())
            .then(data => {
                console.log('Session keep-alive status:', data.status);
            })
            .catch(error => {
                console.warn('Session keep-alive failed:', error);
            });
    }, 5 * 60 * 1000); // 5 minutos

    // ── Cálculo inicial ──────────────────────────────────────────
    calcValDolaresFact();
    calcTotalLiq();
    calcReferencia();
});
</script>