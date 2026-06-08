<?php
// app/Models/Pedimento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedimento extends Model
{
    protected $table = 'pedimentos';

    protected $fillable = [
        'usuario_id',
        // Cabecera
        'num_pedimento',        // 24 47 3434 4000478
        'referencia',           // VT18/478
        'tipo_operacion',       // IMP / EXP
        'cve_pedimento',        // A1
        'regimen',              // IMD
        'destino_origen',       // 9
        'tipo_cambio',          // 19.60370
        'peso_bruto',           // 25.900
        'aduana_entrada_salida',// 470
        // Transportes
        'transporte_entrada_salida', // 4
        'transporte_arribo',         // 4
        'transporte_salida',         // 12
        // Importador
        'rfc_importador',           // VTI920423DA7
        'curp_importador',
        'nombre_importador',        // VISION TRADE INTERNATIONAL S.A. DE C.V.
        'domicilio_importador',     // CERRO DEL AJUSCO...
        // Valores
        'valor_dolares',            // 9,196.48
        'valor_aduana',             // 180,285
        'precio_pagado_valor_comercial', // 156,899
        // Incrementables
        'val_seguros',
        'seguros',
        'fletes',               // 6230
        'embalajes',
        'otros_incrementables', // 17155
        // Decrementables
        'transporte_decrementables',
        'seguro_decrementables',
        'carga_decrementables',
        'descarga_decrementables',
        'otros_decrementables',
        // Fechas y control
        'fecha_entrada',        // 30/08/2024
        'fecha_pago',           // 02/09/2024
        'codigo_aceptacion',    // SV0XJ4NZ
        'clave_seccion_aduanera', // 470
        'nombre_aduana_despacho', // AEROPUERTO INTERNACIONAL DE LA CIUDAD DE MEXICO
        'marcas_numeros_bultos',  // S/M S/N
        'total_bultos',           // 3
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_entrada' => 'date',
        'fecha_pago' => 'date',
    ];

    // ── Relaciones ────────────────────────────────────────
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function partidas()
    {
        return $this->hasMany(Partida::class)->orderBy('sec');
    }

    public function proveedores()
    {
        return $this->hasMany(ProveedorComprador::class, 'pedimento_id');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'pedimento_id');
    }

    public function identificadores()
    {
        return $this->hasMany(IdentificadorPedimento::class, 'pedimento_id');
    }

    public function tasas()
    {
        return $this->hasMany(TasaPedimento::class, 'pedimento_id');
    }

    public function cuadroLiquidacion()
    {
        return $this->hasOne(CuadroLiquidacion::class, 'pedimento_id');
    }

    public function pagoElectronico()
    {
        return $this->hasOne(PagoElectronico::class, 'pedimento_id');
    }

    public function agente()
    {
        return $this->hasOne(AgenteAduanal::class, 'pedimento_id');
    }
}