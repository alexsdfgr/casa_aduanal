{{-- resources/views/pedimentos/imprimir.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedimento_{{ str_replace(' ', '_', $pedimento->num_pedimento) }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }

        .no-print-bar {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-print {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #059669;
        }

        .btn-close-tab {
            background-color: #64748b;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .pedimento-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header-box {
            border: 2px solid #000;
            padding: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 14px;
            font-weight: bold;
        }

        .header-ref {
            font-size: 11px;
            font-weight: bold;
        }

        .sec-title {
            font-weight: bold;
            text-transform: uppercase;
            background-color: #e2e8f0;
            border: 1px solid #000;
            padding: 2px 5px;
            margin-top: 10px;
            margin-bottom: 0;
        }

        table.tbl-print {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        table.tbl-print td, table.tbl-print th {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: middle;
        }

        table.tbl-print th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: left;
        }

        .lbl {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
            background-color: #fafafa;
        }

        .val-bold {
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .qr-section {
            border: 1px solid #000;
            margin-top: 15px;
            padding: 8px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .qr-img {
            width: 110px;
            height: 110px;
        }

        .signature-text {
            font-size: 8px;
            word-break: break-all;
            white-space: normal;
        }

        /* Ocultar elementos en la impresión */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            @page {
                size: letter;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print no-print-bar">
        <div>
            <strong>Vista de Impresión del Pedimento</strong>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i> Imprimir / Guardar como PDF
            </button>
            <button class="btn-close-tab" onclick="window.close()">Cerrar pestaña</button>
        </div>
    </div>

    <div class="pedimento-container">
        
        <div class="header-box">
            <span class="header-title">PEDIMENTO</span>
            <span class="header-ref">REFERENCIA: {{ $pedimento->referencia ?? '—' }}</span>
            <span class="header-title">ANEXO 22</span>
        </div>

        {{-- 1. DATOS GENERALES --}}
        <table class="tbl-print">
            <tbody>
                <tr>
                    <td class="lbl" style="width: 15%;">NUM. PEDIMENTO</td>
                    <td class="val-bold" style="width: 25%;">{{ $pedimento->num_pedimento }}</td>
                    <td class="lbl" style="width: 10%;">T. OPER</td>
                    <td class="val-bold" style="width: 10%;">{{ $pedimento->tipo_operacion }}</td>
                    <td class="lbl" style="width: 15%;">CVE. PEDIMENTO</td>
                    <td style="width: 10%;">{{ $pedimento->cve_pedimento }}</td>
                    <td class="lbl" style="width: 10%;">RÉGIMEN</td>
                    <td style="width: 15%;">{{ $pedimento->regimen }}</td>
                </tr>
                <tr>
                    <td class="lbl">DESTINO/ORIGEN</td>
                    <td>{{ $pedimento->destino_origen }}</td>
                    <td class="lbl">T. CAMBIO</td>
                    <td>{{ number_format($pedimento->tipo_cambio, 5) }}</td>
                    <td class="lbl">PESO BRUTO (KG)</td>
                    <td>{{ number_format($pedimento->peso_bruto, 3) }}</td>
                    <td class="lbl">ADUANA E/S</td>
                    <td>{{ $pedimento->aduana_entrada_salida }}</td>
                </tr>
            </tbody>
        </table>

        {{-- 2. MEDIOS DE TRANSPORTE --}}
        <div class="sec-title">Medios de Transporte</div>
        <table class="tbl-print">
            <tbody>
                <tr>
                    <td class="lbl" style="width: 20%;">ENTRADA/SALIDA</td>
                    <td style="width: 15%;">{{ $pedimento->transporte_entrada_salida ?? '—' }}</td>
                    <td class="lbl" style="width: 15%;">ARRIBO</td>
                    <td style="width: 15%;">{{ $pedimento->transporte_arribo ?? '—' }}</td>
                    <td class="lbl" style="width: 15%;">SALIDA</td>
                    <td style="width: 20%;">{{ $pedimento->transporte_salida ?? '—' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- 3. VALORES --}}
        <div class="sec-title">Valores (Pesos y Dólares)</div>
        <table class="tbl-print">
            <tbody>
                <tr>
                    <td class="lbl" style="width: 20%;">VALOR DÓLARES</td>
                    <td class="val-bold" style="width: 15%;">USD {{ number_format($pedimento->valor_dolares, 2) }}</td>
                    <td class="lbl" style="width: 15%;">VALOR ADUANA</td>
                    <td class="val-bold" style="width: 15%;">$ {{ number_format($pedimento->valor_aduana, 2) }}</td>
                    <td class="lbl" style="width: 20%;">PRECIO PAGADO / COMERCIAL</td>
                    <td class="val-bold" style="width: 15%;">$ {{ number_format($pedimento->precio_pagado_valor_comercial, 2) }}</td>
                </tr>
                <tr>
                    <td class="lbl">VAL. SEGUROS</td>
                    <td>{{ number_format($pedimento->val_seguros, 2) }}</td>
                    <td class="lbl">SEGUROS</td>
                    <td>{{ number_format($pedimento->seguros, 2) }}</td>
                    <td class="lbl">FLETES</td>
                    <td>{{ number_format($pedimento->fletes, 2) }}</td>
                </tr>
                <tr>
                    <td class="lbl">EMBALAJES</td>
                    <td>{{ number_format($pedimento->embalajes, 2) }}</td>
                    <td class="lbl">OTROS INCR.</td>
                    <td>{{ number_format($pedimento->otros_incrementables, 2) }}</td>
                    <td class="lbl">DECREMENTABLES</td>
                    <td>
                        $ {{ number_format(
                            ($pedimento->transporte_decrementables ?? 0) + 
                            ($pedimento->seguro_decrementables ?? 0) + 
                            ($pedimento->carga_decrementables ?? 0) + 
                            ($pedimento->descarga_decrementables ?? 0) + 
                            ($pedimento->otros_decrementables ?? 0), 2
                        ) }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- 4. IMPORTADOR --}}
        <div class="sec-title">Datos del Importador / Exportador</div>
        <table class="tbl-print">
            <tbody>
                <tr>
                    <td class="lbl" style="width: 15%;">RFC</td>
                    <td class="val-bold" style="width: 35%;">{{ $pedimento->rfc_importador }}</td>
                    <td class="lbl" style="width: 15%;">CURP</td>
                    <td style="width: 35%;">{{ $pedimento->curp_importador ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="lbl">NOMBRE/RAZÓN</td>
                    <td colspan="3" class="val-bold">{{ $pedimento->nombre_importador }}</td>
                </tr>
                <tr>
                    <td class="lbl">DOMICILIO</td>
                    <td colspan="3">{{ $pedimento->domicilio_importador }}</td>
                </tr>
            </tbody>
        </table>

        {{-- 5. FECHAS Y CONTROL --}}
        <div class="sec-title">Fechas y Control de Aduana</div>
        <table class="tbl-print">
            <tbody>
                <tr>
                    <td class="lbl" style="width: 15%;">FECHA ENTRADA</td>
                    <td style="width: 18%;">{{ $pedimento->fecha_entrada?->format('d/m/Y') ?? '—' }}</td>
                    <td class="lbl" style="width: 15%;">FECHA PAGO</td>
                    <td style="width: 18%;">{{ $pedimento->fecha_pago?->format('d/m/Y') ?? '—' }}</td>
                    <td class="lbl" style="width: 15%;">TOTAL BULTOS</td>
                    <td style="width: 19%;">{{ $pedimento->total_bultos }}</td>
                </tr>
                <tr>
                    <td class="lbl">SECC. ADUANERA</td>
                    <td>{{ $pedimento->clave_seccion_aduanera ?? '—' }}</td>
                    <td class="lbl">ADUANA DESPACHO</td>
                    <td colspan="3">{{ $pedimento->nombre_aduana_despacho ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="lbl">MARCAS Y BULTO</td>
                    <td colspan="5">{{ $pedimento->marcas_numeros_bultos ?? '—' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- 6. PROVEEDORES --}}
        @if($pedimento->proveedores->count())
            <div class="sec-title">Datos del Proveedor / Comprador</div>
            <table class="tbl-print">
                <thead>
                    <tr>
                        <th style="width: 20%;">ID Fiscal</th>
                        <th style="width: 30%;">Nombre</th>
                        <th style="width: 40%;">Domicilio</th>
                        <th style="width: 10%;">Vinculación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedimento->proveedores as $pr)
                    <tr>
                        <td class="val-bold">{{ $pr->id_fiscal }}</td>
                        <td>{{ $pr->nombre }}</td>
                        <td>{{ $pr->domicilio }}</td>
                        <td>{{ $pr->vinculacion }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- 7. FACTURAS --}}
        @if($pedimento->facturas->count())
            <div class="sec-title">Facturas / Documentos de Valor</div>
            <table class="tbl-print">
                <thead>
                    <tr>
                        <th>CFDI</th>
                        <th>Núm. Factura</th>
                        <th>Fecha</th>
                        <th>Incoterm</th>
                        <th>Moneda</th>
                        <th class="text-end">Val. Moneda</th>
                        <th>Factor</th>
                        <th class="text-end">Val. USD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedimento->facturas as $fac)
                    <tr>
                        <td>{{ $fac->num_cfdi }}</td>
                        <td>{{ $fac->num_factura ?? '—' }}</td>
                        <td>{{ $fac->fecha?->format('d/m/Y') }}</td>
                        <td>{{ $fac->incoterm }}</td>
                        <td>{{ $fac->moneda_factura }}</td>
                        <td class="text-end">{{ number_format($fac->val_moneda_fact, 2) }}</td>
                        <td>{{ $fac->factor_moneda }}</td>
                        <td class="text-end val-bold">{{ number_format($fac->val_dolares, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- 8. LIQUIDACIÓN --}}
        @if($pedimento->cuadroLiquidacion)
            @php $liq = $pedimento->cuadroLiquidacion; @endphp
            <div class="sec-title">Cuadro de Liquidación</div>
            <table class="tbl-print">
                <thead>
                    <tr>
                        <th>Concepto Izq</th>
                        <th>F.P.</th>
                        <th class="text-end">Importe</th>
                        <th>Concepto Der</th>
                        <th>F.P.</th>
                        <th class="text-end">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $liq->concepto_izq ?? '—' }}</td>
                        <td>{{ $liq->fp_izq }}</td>
                        <td class="text-end">{{ number_format($liq->importe_izq, 2) }}</td>
                        <td>{{ $liq->concepto_der ?? '—' }}</td>
                        <td>{{ $liq->fp_der }}</td>
                        <td class="text-end">{{ number_format($liq->importe_der, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">EFECTIVO</td>
                        <td class="val-bold text-end" colspan="2">$ {{ number_format($liq->efectivo, 2) }}</td>
                        <td class="lbl">OTROS</td>
                        <td class="val-bold text-end" colspan="2">$ {{ number_format($liq->otros, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="lbl" colspan="3">TOTAL LIQUIDADO</td>
                        <td class="val-bold text-end" colspan="3" style="font-size: 11px;">$ {{ number_format($liq->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        {{-- 9. PARTIDAS --}}
        @if($pedimento->partidas->count())
            <div class="sec-title">Partidas y Contribuciones por Partida</div>
            @foreach($pedimento->partidas as $partida)
                <table class="tbl-print" style="margin-top: 5px; border: 2px solid #000;">
                    <tbody>
                        <tr style="background-color: #fafafa;">
                            <td class="lbl" style="width: 10%;">Secuencia</td>
                            <td class="val-bold" style="width: 10%;">{{ str_pad($partida->sec, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="lbl" style="width: 15%;">Fracción Arancel.</td>
                            <td class="val-bold" style="width: 20%;">{{ $partida->fraccion }}</td>
                            <td class="lbl" style="width: 10%;">NICO</td>
                            <td style="width: 10%;">{{ $partida->subd_nico ?? '—' }}</td>
                            <td class="lbl" style="width: 10%;">País O/D</td>
                            <td style="width: 15%;">{{ $partida->p_od }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">UMC / Cant.</td>
                            <td>{{ $partida->umc }} / {{ number_format($partida->cantidad_umc, 3) }}</td>
                            <td class="lbl">Valor USD</td>
                            <td class="val-bold">USD {{ number_format($partida->val_adu_usd, 2) }}</td>
                            <td class="lbl">Precio Unit.</td>
                            <td colspan="3">{{ number_format($partida->precio_unit, 5) }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Descripción</td>
                            <td colspan="7">{{ $partida->descripcion }}</td>
                        </tr>
                        @if($partida->contribuciones->count())
                            <tr>
                                <td colspan="8" style="padding: 0;">
                                    <table class="tbl-print" style="margin: 0; border: none;">
                                        <thead>
                                            <tr style="background-color: #f1f5f9;">
                                                <th style="padding: 2px 5px; font-size: 8px;">Contribución</th>
                                                <th style="padding: 2px 5px; font-size: 8px;">Tipo Tasa</th>
                                                <th style="padding: 2px 5px; font-size: 8px;">Tasa</th>
                                                <th style="padding: 2px 5px; font-size: 8px;">F.P.</th>
                                                <th class="text-end" style="padding: 2px 5px; font-size: 8px;">Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($partida->contribuciones as $c)
                                            <tr>
                                                <td style="padding: 2px 5px; font-size: 9px;" class="val-bold">{{ $c->con }}</td>
                                                <td style="padding: 2px 5px; font-size: 9px;">{{ $c->tt }}</td>
                                                <td style="padding: 2px 5px; font-size: 9px;">{{ number_format($c->tasa, 5) }}</td>
                                                <td style="padding: 2px 5px; font-size: 9px;">{{ $c->fp }}</td>
                                                <td class="text-end" style="padding: 2px 5px; font-size: 9px;">$ {{ number_format($c->importe, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endforeach
        @endif

        {{-- FIN DE PEDIMENTO --}}
        <div class="text-center" style="font-weight: bold; margin: 10px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 3px 0;">
            *** FIN DEL DOCUMENTO - TOTAL DE PARTIDAS: {{ $pedimento->partidas->count() }} ***
        </div>

        {{-- 10. VALIDACIÓN / AGENTE --}}
        @if($pedimento->agente)
            <div class="sec-title">Agente Aduanal o Representante Legal</div>
            <table class="tbl-print">
                <tbody>
                    <tr>
                        <td class="lbl" style="width: 20%;">Nombre / Razón</td>
                        <td class="val-bold" style="width: 50%;">{{ $pedimento->agente->nombre }}</td>
                        <td class="lbl" style="width: 15%;">Patente</td>
                        <td class="val-bold" style="width: 15%;">{{ $pedimento->agente->patente }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Firma Electrónica / e.firma</td>
                        <td colspan="3" class="signature-text">{{ $pedimento->agente->efirma ?? 'SIN FIRMA ELECTRÓNICA REGISTRADA' }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        {{-- 11. QR DE VALIDACIÓN SAT --}}
        <div class="qr-section">
            <div>
                <img class="qr-img" src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode($qrData) }}" alt="Código QR SAT de Validación" />
            </div>
            <div style="flex-grow: 1;">
                <strong style="text-transform: uppercase;">Código de Validación del Pedimento</strong>
                <p style="margin: 3px 0 0 0; line-height: 1.3; font-size: 9px;">
                    Este código certifica la validación y pago electrónico del presente pedimento ante la aduana correspondiente, en conformidad con lo establecido en las Reglas Generales de Comercio Exterior vigentes.
                </p>
                <div class="signature-text" style="margin-top: 5px; background-color: #fafafa; border: 1px solid #ddd; padding: 3px;">
                    <strong>Firma Digital de Validación:</strong> {{ $pedimento->codigo_aceptacion ?? 'PND-VAL-SAT-ERROR' }}
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lanzar el cuadro de diálogo de impresión automáticamente tras cargar
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
