{{-- resources/views/pedimentos/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Pedimento ' . $pedimento->num_pedimento)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('pedimentos.index') }}">Pedimentos</a></li>
    <li class="breadcrumb-item active">{{ $pedimento->num_pedimento }}</li>
@endsection

@section('content')

    <style>
        :root {
            --adu-dark: #1e293b;
            --adu-blue: #38bdf8;
            --adu-gold: #fde047;
            --adu-sub-bg: rgba(15, 23, 42, 0.4);
            --adu-row-alt: rgba(30, 41, 59, 0.5);
            --adu-label-color: #cbd5e1;
            --adu-label-size: .68rem;
            --adu-cell-size: .78rem;
        }

        .lbl {
            font-size: var(--adu-label-size);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--adu-label-color);
        }

        .tbl-adu {
            font-size: var(--adu-cell-size);
            margin-bottom: 0;
            border-collapse: collapse;
            width: 100%;
            color: #f8fafc;
        }

        .tbl-adu th,
        .tbl-adu td {
            border: 1px solid #334155;
            padding: .28rem .55rem;
            vertical-align: middle;
        }

        .tbl-adu thead tr {
            background: var(--adu-dark);
            color: #fff;
        }

        .tbl-adu thead th {
            font-size: var(--adu-label-size);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-color: #334155;
        }

        .tbl-adu tbody tr:nth-child(even) {
            background: var(--adu-row-alt);
        }

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
        }

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

        .card-adu-header .ref {
            font-size: .76rem;
            font-weight: 400;
        }

        .card-adu-header .gold {
            color: var(--adu-gold);
            font-size: .72rem;
        }

        .fc {
            font-family: 'Courier New', monospace;
        }

        .linea-captura {
            font-family: 'Courier New', monospace;
            font-size: .95rem;
            font-weight: 700;
            letter-spacing: .12em;
            color: var(--adu-blue);
            background: #0f172a;
            border: 1px dashed #334155;
            border-radius: 4px;
            padding: .45rem .9rem;
            display: inline-block;
        }

        .sum-box {
            background: var(--adu-row-alt);
            border-radius: 4px;
            min-width: 120px;
            padding: .38rem .85rem;
            text-align: center;
        }

        .sum-box .lbl-s {
            font-size: .62rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
        }

        .sum-box .val-s {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            font-size: .85rem;
        }

        .total-box {
            background: var(--adu-dark);
            border-radius: 4px;
            min-width: 150px;
            padding: .38rem .85rem;
            text-align: center;
        }

        .total-box .lbl-total {
            font-size: .62rem;
            color: rgba(255, 255, 255, .55);
            font-weight: 600;
            text-transform: uppercase;
        }

        .total-box .val-total {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: var(--adu-gold);
            font-size: 1.05rem;
        }
    </style>

    {{-- BARRA DE HERRAMIENTAS --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <h5 class="fw-bold mb-0" style="color:var(--adu-dark)">Pedimento {{ $pedimento->num_pedimento }}</h5>
            @php
                $badge = match ($pedimento->estado) {
                    'borrador' => 'secondary',
                    'transmitido' => 'info',
                    'pagado' => 'warning',
                    'liberado' => 'success',
                    default => 'secondary'
                };
            @endphp
            <span class="badge bg-{{ $badge }}">{{ ucfirst($pedimento->estado) }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pedimentos.imprimir', $pedimento) }}" target="_blank" class="btn btn-sm btn-success">
                <i class="bi bi-printer me-1"></i> Imprimir
            </a>
            <a href="{{ route('pedimentos.edit', $pedimento) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('pedimentos.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Regresar
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
    TARJETA ÚNICA — todo el documento en un solo bloque
    ══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border:2px solid var(--adu-dark);border-radius:4px;overflow:hidden">

        <div class="card-adu-header">
            <strong>PEDIMENTO</strong>
            <span class="ref">REF: {{ $pedimento->referencia ?? '—' }}</span>
            <span class="gold">DOCUMENTO COMPLETO</span>
        </div>

        <div class="card-body p-0">

            {{-- Datos Generales --}}
            <table class="tbl-adu">
                <tbody>
                    <tr style="background:var(--adu-row-alt)">
                        <td class="lbl" style="width:130px">Núm. Pedimento</td>
                        <td class="fc fw-bold" style="color:var(--adu-dark)">{{ $pedimento->num_pedimento }}</td>
                        <td class="lbl" style="width:80px">T. Oper</td>
                        <td>
                            <span
                                class="badge {{ $pedimento->tipo_operacion === 'IMP' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">
                                {{ $pedimento->tipo_operacion }}
                            </span>
                        </td>
                        <td class="lbl" style="width:110px">Cve. Pedimento</td>
                        <td class="fc">{{ $pedimento->cve_pedimento }}</td>
                        <td class="lbl" style="width:75px">Régimen</td>
                        <td><code>{{ $pedimento->regimen }}</code></td>
                    </tr>
                    <tr>
                        <td class="lbl">Destino/Origen</td>
                        <td class="fc">{{ $pedimento->destino_origen }}</td>
                        <td class="lbl">Tipo Cambio</td>
                        <td class="fc">{{ number_format($pedimento->tipo_cambio, 5) }}</td>
                        <td class="lbl">Peso Bruto</td>
                        <td class="fc">{{ number_format($pedimento->peso_bruto, 3) }} kg</td>
                        <td class="lbl">Aduana E/S</td>
                        <td class="fc">{{ $pedimento->aduana_entrada_salida }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Medios de Transporte --}}
            <div class="sec-header">Medios de Transporte</div>
            <table class="tbl-adu">
                <tbody>
                    <tr>
                        <td class="lbl" style="width:130px">Entrada/Salida</td>
                        <td class="fc">{{ $pedimento->transporte_entrada_salida ?? '—' }}</td>
                        <td class="lbl" style="width:80px">Arribo</td>
                        <td class="fc">{{ $pedimento->transporte_arribo ?? '—' }}</td>
                        <td class="lbl" style="width:80px">Salida</td>
                        <td class="fc">{{ $pedimento->transporte_salida ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Valores --}}
            <div class="sec-header">Valores</div>
            <table class="tbl-adu">
                <tbody>
                    <tr>
                        <td class="lbl" style="width:130px">Valor Dólares</td>
                        <td class="fc fw-semibold">USD {{ number_format($pedimento->valor_dolares, 2) }}</td>
                        <td class="lbl" style="width:110px">Valor Aduana</td>
                        <td class="fc fw-semibold">$ {{ number_format($pedimento->valor_aduana, 2) }}</td>
                        <td class="lbl">Precio Pagado / Val. Comercial</td>
                        <td class="fc fw-semibold">$ {{ number_format($pedimento->precio_pagado_valor_comercial, 2) }}</td>
                    </tr>
                    <tr style="background:var(--adu-row-alt)">
                        <td class="lbl">Val. Seguros</td>
                        <td class="fc">{{ number_format($pedimento->val_seguros, 2) }}</td>
                        <td class="lbl">Seguros</td>
                        <td class="fc">{{ number_format($pedimento->seguros, 2) }}</td>
                        <td class="lbl">Fletes</td>
                        <td class="fc">{{ number_format($pedimento->fletes, 2) }}</td>
                    </tr>
                    <tr style="background:var(--adu-row-alt)">
                        <td class="lbl">Embalajes</td>
                        <td class="fc">{{ number_format($pedimento->embalajes, 2) }}</td>
                        <td class="lbl">Otros Incr.</td>
                        <td class="fc">{{ number_format($pedimento->otros_incrementables, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>

            {{-- Importador / Exportador --}}
            <div class="sec-header">Datos del Importador / Exportador</div>
            <table class="tbl-adu">
                <tbody>
                    <tr>
                        <td class="lbl" style="width:50px">RFC</td>
                        <td class="fc fw-semibold">{{ $pedimento->rfc_importador }}</td>
                        <td class="lbl" style="width:50px">CURP</td>
                        <td class="fc">{{ $pedimento->curp_importador ?? '—' }}</td>
                    </tr>
                    <tr style="background:var(--adu-row-alt)">
                        <td class="lbl">Nombre</td>
                        <td colspan="3" class="fw-semibold">{{ $pedimento->nombre_importador }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Domicilio</td>
                        <td colspan="3">{{ $pedimento->domicilio_importador }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Fechas & Identificación --}}
            <div class="sec-header">Fechas &amp; Identificación</div>
            <table class="tbl-adu">
                <tbody>
                    <tr>
                        <td class="lbl" style="width:110px">F. Entrada</td>
                        <td class="fc">{{ $pedimento->fecha_entrada?->format('d/m/Y') ?? '—' }}</td>
                        <td class="lbl" style="width:75px">F. Pago</td>
                        <td class="fc" colspan="3">{{ $pedimento->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                        <td class="lbl" style="width:100px">Total Bultos</td>
                        <td class="fc">{{ $pedimento->total_bultos }}</td>
                    </tr>
                    <tr style="background:var(--adu-row-alt)">
                        <td class="lbl">Secc. Aduanera</td>
                        <td class="fc">{{ $pedimento->clave_seccion_aduanera ?? '—' }}</td>
                        <td colspan="2">{{ $pedimento->nombre_aduana_despacho ?? '—' }}</td>
                        <td class="lbl">Marcas/Núm/Bultos</td>
                        <td class="fc" colspan="3">{{ $pedimento->marcas_numeros_bultos ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
            {{-- ================= PARTIDAS ================= --}}
            @if($pedimento->partidas->count())

                <div class="sec-header">Partidas</div>

                <table class="tbl-adu">
                    <thead>
                        <tr>
                            <th>Sec</th>
                            <th>Fracción</th>
                            <th>NICO</th>
                            <th>Descripción</th>
                            <th>UMC</th>
                            <th>Cant UMC</th>
                            <th>Valor USD</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($pedimento->partidas as $partida)
                            <tr>
                                <td class="fc">{{ $partida->sec }}</td>
                                <td class="fc">{{ $partida->fraccion }}</td>
                                <td class="fc">{{ $partida->subd_nico }}</td>
                                <td>{{ $partida->descripcion }}</td>
                                <td class="fc">{{ $partida->umc }}</td>
                                <td class="fc">{{ $partida->cantidad_umc }}</td>
                                <td class="fc">$ {{ number_format($partida->val_adu_usd, 2) }}</td>
                            </tr>
                            {{-- SUBTABLA CONTRIBUCIONES --}}
                            @if($partida->contribuciones->count())
                                <tr>
                                    <td colspan="7" style="padding:0">
                                        <table class="tbl-adu mb-0">
                                            <thead>
                                                <tr style="background:#1e293b;color:#fff">
                                                    <th>Contribución</th>
                                                    <th>Tipo</th>
                                                    <th>Tasa</th>
                                                    <th>Importe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($partida->contribuciones as $c)
                                                    <tr>
                                                        {{-- Cambiado de $c->contribucion a $c->con --}}
                                                        <td class="fc fw-semibold">{{ $c->con }}</td>

                                                        {{-- Cambiado de $c->tipo_tasa a $c->tt --}}
                                                        <td>{{ $c->tt ?? '—' }}</td>

                                                        <td class="fc">{{ number_format($c->tasa, 5) }}</td>
                                                        <td>{{ $c->fp }}</td>
                                                        <td class="text-end fc">$ {{ number_format($c->importe, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif

                            {{-- SUBTABLA IDENTIFICADORES --}}
                            @if($partida->identificadores->count())
                                <tr>
                                    <td colspan="7" style="padding:0">
                                        <table class="tbl-adu mb-0" style="background-color:#f8f9fa;">
                                            <thead>
                                                <tr style="background:#475569;color:#fff">
                                                    <th>Identificador</th>
                                                    <th>Complemento 1</th>
                                                    <th>Complemento 2</th>
                                                    <th>Complemento 3</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($partida->identificadores as $id)
                                                    <tr>
                                                        <td class="fc fw-semibold">{{ $id->clave }}</td>
                                                        <td>{{ $id->c1 ?? '—' }}</td>
                                                        <td>{{ $id->c2 ?? '—' }}</td>
                                                        <td>{{ $id->c3 ?? '—' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif

                        @endforeach

                    </tbody>
                </table>

                <div class="text-end mt-1" style="font-size:.7rem;color:#64748b">
                    *** FIN DE PARTIDAS *** NÚM. TOTAL DE PARTIDAS: {{ $pedimento->partidas->count() }} ***
                </div>

            @endif

            {{-- Tasas a Nivel Pedimento --}}
            @if($pedimento->tasas->count())
                <div class="sec-header">Tasas a Nivel Pedimento</div>
                <table class="tbl-adu">
                    <thead>
                        <tr>
                            <th>Contrib.</th>
                            <th>Cve. T. Tasa</th>
                            <th>Tasa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedimento->tasas as $t)
                            <tr>
                                <td class="fc">{{ $t->contribucion }}</td>
                                <td>{{ $t->cve_tipo_tasa }}</td>
                                <td class="fc">{{ number_format($t->tasa, 5) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Cuadro de Liquidación --}}
            @if($pedimento->cuadroLiquidacion)
                @php $liq = $pedimento->cuadroLiquidacion; @endphp
                <div class="sec-header">Cuadro de Liquidación</div>
                <div class="p-3">
                    <table class="tbl-adu mb-2">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th>F.P.</th>
                                <th class="text-end">Importe</th>
                                <th>Concepto</th>
                                <th>F.P.</th>
                                <th class="text-end">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fc fw-semibold">{{ $liq->concepto_izq }}</td>
                                <td>{{ $liq->fp_izq }}</td>
                                <td class="text-end fc">$ {{ number_format($liq->importe_izq, 2) }}</td>
                                <td class="fc fw-semibold">{{ $liq->concepto_der ?? '—' }}</td>
                                <td>{{ $liq->fp_der }}</td>
                                <td class="text-end fc">$ {{ number_format($liq->importe_der, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end gap-2">
                        <div class="sum-box">
                            <div class="lbl-s">Efectivo</div>
                            <div class="val-s">$ {{ number_format($liq->efectivo, 2) }}</div>
                        </div>
                        <div class="sum-box">
                            <div class="lbl-s">Otros</div>
                            <div class="val-s">$ {{ number_format($liq->otros, 2) }}</div>
                        </div>
                        <div class="total-box">
                            <div class="lbl-total">Total</div>
                            <div class="val-total">$ {{ number_format($liq->total, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Depósito Referenciado --}}
            @if($pedimento->pagoElectronico)
                @php $pg = $pedimento->pagoElectronico; @endphp
                <div class="sec-header">Depósito Referenciado – Línea de Captura</div>
                <div class="p-3">
                    <div class="mb-2">
                        <span class="linea-captura">{{ $pg->linea_captura }}</span>
                    </div>
                    <table class="tbl-adu">
                        <tbody>
                            <tr>
                                <td class="lbl" style="width:80px">Patente</td>
                                <td class="fc">{{ $pg->patente }}</td>
                                <td class="lbl" style="width:70px">Aduana</td>
                                <td class="fc">{{ $pg->aduana }}</td>
                                <td class="lbl" style="width:140px">Institución Bancaria</td>
                                <td>{{ $pg->nombre_institucion }}</td>
                            </tr>
                            <tr style="background:var(--adu-row-alt)">
                                <td class="lbl">Importe Pagado</td>
                                <td class="fc fw-bold" style="color:#166534">$ {{ number_format($pg->importe_pagado, 2) }}</td>
                                <td class="lbl">Fecha Pago</td>
                                <td class="fc" colspan="3">{{ $pg->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Proveedor / Comprador --}}
            @if($pedimento->proveedores->count())
                <div class="sec-header">Datos del Proveedor / Comprador</div>
                <table class="tbl-adu">
                    <thead>
                        <tr>
                            <th>ID Fiscal</th>
                            <th>Nombre</th>
                            <th>Domicilio</th>
                            <th>Vinculación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedimento->proveedores as $pr)
                            <tr>
                                <td class="fc fw-semibold">{{ $pr->id_fiscal }}</td>
                                <td>{{ $pr->nombre }}</td>
                                <td>{{ $pr->domicilio }}</td>
                                <td>
                                    <span class="badge {{ $pr->vinculacion === 'SI' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                        {{ $pr->vinculacion }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Facturas --}}
            @if($pedimento->facturas->count())
                <div class="sec-header">Factura / Documento de Valor</div>
                <table class="tbl-adu">
                    <thead>
                        <tr>
                            <th>CFDI</th>
                            <th>Núm. Factura</th>
                            <th>Fecha</th>
                            <th>Incoterm</th>
                            <th>Moneda</th>
                            <th class="text-end">Val. Moneda Fact.</th>
                            <th>Factor</th>
                            <th class="text-end">Val. Dólares</th>
                            <th>Guía</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedimento->facturas as $fac)
                            <tr>
                                <td class="fc">{{ $fac->num_cfdi }}</td>
                                <td class="fc">{{ $fac->num_factura ?? '—' }}</td>
                                <td class="fc">{{ $fac->fecha?->format('d/m/Y') }}</td>
                                <td><span class="badge bg-secondary">{{ $fac->incoterm }}</span></td>
                                <td class="fc">{{ $fac->moneda_factura }}</td>
                                <td class="text-end fc">{{ number_format($fac->val_moneda_fact, 2) }}</td>
                                <td class="fc">{{ $fac->factor_moneda }}</td>
                                <td class="text-end fc fw-semibold">{{ number_format($fac->val_dolares, 2) }}</td>
                                <td class="fc">{{ $fac->no_guia_embarque ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- ── PARTIDAS ────────────────────────────────────────────────────────── --}}
            <div class="sec-header">
                Partidas
                <span class="badge bg-secondary ms-1" style="font-size:.62rem">{{ $pedimento->partidas->count() }}</span>
            </div>

            @forelse($pedimento->partidas as $partida)
                {{-- Sub-header de cada partida --}}
                <div
                    style="background:#1e2d45;color:#fff;padding:.26rem .75rem;font-size:.7rem;font-weight:700;display:flex;align-items:center;gap:.6rem;border-top:1px solid #2d3f5e">
                    <span class="fc">{{ str_pad($partida->sec, 3, '0', STR_PAD_LEFT) }}</span>
                    <span style="opacity:.4">|</span>
                    <span>PARTIDA</span>
                </div>

                <table class="tbl-adu">
                    <tbody>
                        <tr style="background:var(--adu-row-alt)">
                            <td class="lbl" style="width:90px">Fracción</td>
                            <td class="fc fw-bold" style="color:var(--adu-dark);font-size:.9rem">
                                {{ $partida->fraccion }}
                            </td>
                            <td class="lbl" style="width:75px">País O/D</td>
                            <td class="fc">{{ $partida->p_od }}</td>
                            <td class="lbl" style="width:110px">UMC / Cantidad</td>
                            <td class="fc">{{ $partida->umc }} {{ number_format($partida->cantidad_umc, 3) }}</td>
                            <td class="lbl" style="width:120px">Val. Aduana USD</td>
                            <td class="fc fw-semibold">USD {{ number_format($partida->val_adu_usd, 2) }}</td>
                            <td class="lbl" style="width:90px">Precio Unit.</td>
                            <td class="fc">{{ number_format($partida->precio_unit, 5) }}</td>
                            <td class="lbl" style="width:90px">Vinculación</td>
                            <td>
                                <span class="badge {{ $partida->vinc === '1' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                    {{ $partida->vinc === '1' ? 'SÍ' : 'NO' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="lbl">Descripción</td>
                            <td colspan="11" style="font-size:.85rem">{{ $partida->descripcion }}</td>
                        </tr>
                    </tbody>
                </table>

                @if($partida->marca || $partida->modelo || $partida->codigo_producto)
                    <div class="d-flex gap-2 px-2 py-1" style="background:#fafafa;border-top:1px solid #dee2e6">
                        @if($partida->marca)
                            <span class="badge bg-light text-dark border"><i class="bi bi-tag me-1"></i>{{ $partida->marca }}</span>
                        @endif
                        @if($partida->modelo)
                            <span class="badge bg-light text-dark border"><i class="bi bi-cpu me-1"></i>{{ $partida->modelo }}</span>
                        @endif
                        @if($partida->codigo_producto)
                            <span class="badge bg-light text-dark border"><i
                                    class="bi bi-upc me-1"></i>{{ $partida->codigo_producto }}</span>
                        @endif
                    </div>
                @endif

                @if($partida->contribuciones->count())
                    <div class="sec-header" style="background:#dde8f4">Contribuciones de Partida</div>
                    <table class="tbl-adu">
                        <thead>
                            <tr>
                                <th>Contribución</th>
                                <th>Tipo Tasa</th>
                                <th>Tasa</th>
                                <th>F.P.</th>
                                <th class="text-end">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partida->contribuciones as $c)
                                <tr>
                                    <td class="fc fw-semibold">{{ $c->con }}</td>
                                    <td>{{ $c->tt }}</td>
                                    <td class="fc">{{ number_format($c->tasa, 5) }}</td>
                                    <td>{{ $c->fp }}</td>
                                    <td class="text-end fc">$ {{ number_format($c->importe, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if($partida->identificadores->count())
                    <div class="sec-header" style="background:#e8f4f0">Identificadores de Partida</div>
                    <table class="tbl-adu">
                        <thead>
                            <tr>
                                <th>Clave</th>
                                <th>Complemento 1</th>
                                <th>Complemento 2</th>
                                <th>Complemento 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partida->identificadores as $id)
                                <tr>
                                    <td class="fc fw-semibold">{{ $id->clave }}</td>
                                    <td>{{ $id->c1 ?? '—' }}</td>
                                    <td>{{ $id->c2 ?? '—' }}</td>
                                    <td>{{ $id->c3 ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @empty
                <div class="p-3 text-center text-muted" style="font-size:.82rem">
                    <i class="bi bi-inbox me-1"></i> Este pedimento no contiene partidas registradas.
                </div>
            @endforelse

            {{-- FIN DE PEDIMENTO --}}
            @if($pedimento->partidas->count())
                <div class="text-center py-2"
                    style="font-size:.72rem;color:#64748b;border-top:1px solid #dee2e6;border-bottom:1px solid #dee2e6;background:#fafafa">
                    *** FIN DE PEDIMENTO *** NÚM. TOTAL DE PARTIDAS: {{ $pedimento->partidas->count() }} ***
                </div>
            @endif

            {{-- ── AGENTE ADUANAL ──────────────────────────────────────────────────── --}}
            @if($pedimento->agente)
                @php $ag = $pedimento->agente; @endphp
                <div class="sec-header" style="display:flex;justify-content:space-between;align-items:center">
                    <span><i class="bi bi-person-badge me-1"></i> Agente Aduanal / Representante Legal</span>
                    <span style="font-weight:400;font-size:.65rem;color:#1e40af">PATENTE: {{ $ag->patente }}</span>
                </div>
                <table class="tbl-adu">
                    <tbody>
                        <tr>
                            <td class="lbl" style="width:140px">Nombre / Razón Social</td>
                            <td colspan="3">{{ $ag->nombre }} {{ $ag->razon_social ? '— ' . $ag->razon_social : '' }}</td>
                        </tr>
                        <tr style="background:var(--adu-row-alt)">
                            <td class="lbl">RFC</td>
                            <td class="fc">{{ $ag->rfc }}</td>
                            <td class="lbl" style="width:80px">Patente</td>
                            <td class="fc fw-bold" style="color:var(--adu-dark);font-size:1rem">{{ $ag->patente }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Núm. Serie Certificado</td>
                            <td class="fc" style="font-size:.72rem" colspan="3">{{ $ag->num_serie_certificado ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>{{-- /card-body --}}
    </div>{{-- /tarjeta única --}}

@endsection