<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── USUARIOS ─────────────────────────────────────────────────────
        DB::table('usuarios')->insertOrIgnore([
            [
                'nombre'     => 'Administrador Sistema',
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'rol'        => 'ADMIN',
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre'     => 'Prof. García López',
                'username'   => 'profesor',
                'password'   => Hash::make('profesor123'),
                'rol'        => 'PROFESOR',
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre'     => 'Alumno Demo',
                'username'   => 'alumno',
                'password'   => Hash::make('alumno123'),
                'rol'        => 'ALUMNO',
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $adminId = DB::table('usuarios')->where('username', 'admin')->value('id');

        // ── PEDIMENTO DE EJEMPLO 24 47 3434 4000478 ───────────────────────
        $pedId = DB::table('pedimentos')->insertGetId([
            'usuario_id'                     => $adminId,
            'num_pedimento'                  => '24 47 3434 4000478',
            'referencia'                     => 'VT18/478',
            'tipo_operacion'                 => 'IMP',
            'cve_pedimento'                  => 'A1',
            'regimen'                        => 'IMD',
            'destino_origen'                 => '9',
            'tipo_cambio'                    => '19.60370',
            'peso_bruto'                     => '25.900',
            'aduana_entrada_salida'          => '470',
            'transporte_entrada_salida'      => '4',
            'transporte_arribo'              => '4',
            'transporte_salida'              => '12',
            'rfc_importador'                 => 'VTI900101AA0',
            'curp_importador'                => null,
            'nombre_importador'              => 'VISION TRADE INTERNATIONAL S.A. DE C.V.',
            'domicilio_importador'           => 'AV. INSURGENTES SUR 1898, PISO 3, FLORIDA, CDMX, 01030, MEXICO',
            'valor_dolares'                  => '179492.00',
            'valor_aduana'                   => '3517745.06',
            'precio_pagado_valor_comercial'  => '3361535.46',
            'val_seguros'                    => '0',
            'seguros'                        => '0',
            'fletes'                         => '6230.00',
            'embalajes'                      => '0',
            'otros_incrementables'           => '17155.00',
            'transporte_decrementables'      => '0',
            'seguro_decrementables'          => '0',
            'carga_decrementables'           => '0',
            'descarga_decrementables'        => '0',
            'otros_decrementables'           => '0',
            'fecha_entrada'                  => '2024-09-18',
            'fecha_pago'                     => '2024-09-18',
            'codigo_aceptacion'              => '24925F9F12',
            'clave_seccion_aduanera'         => '4700',
            'nombre_aduana_despacho'         => 'A.I. DE LA CIUDAD DE MEXICO',
            'marcas_numeros_bultos'          => 'S/M S/N',
            'total_bultos'                   => '4',
            'observaciones'                  => 'SE TRANSMITE FACTURA COMERCIAL Y COVE CONFORME ART. 36-A FRACC. I LA.',
            'estado'                         => 'liberado',
            'created_at'                     => now(),
            'updated_at'                     => now(),
        ]);

        DB::table('proveedores_compradores')->insert([
            'pedimento_id' => $pedId, 'id_fiscal' => 'GB880391804',
            'nombre' => 'LINX PRINTING TECHNOLOGIES LTD',
            'domicilio' => '8 INDUSTRIAL ESTATE, CAMBRIDGE, CB1 3EN, UNITED KINGDOM',
            'vinculacion' => 'NO', 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('facturas')->insert([
            'pedimento_id' => $pedId, 'num_cfdi' => 'COVE247092RQ4',
            'num_factura' => '5010031480', 'fecha' => '2024-09-06',
            'incoterm' => 'FCA', 'moneda_factura' => 'USD',
            'val_moneda_fact' => '156209.00', 'factor_moneda' => '1.00000000',
            'val_dolares' => '156209.00', 'no_guia_embarque' => 'AMS180924CB01',
            'id_embarque' => null, 'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('cuadro_liquidacion')->insert([
            'pedimento_id' => $pedId, 'concepto_izq' => 'DTA', 'fp_izq' => 0,
            'importe_izq' => '1442.00', 'concepto_der' => 'PRV', 'fp_der' => 0,
            'importe_der' => '290.00', 'efectivo' => '30900.00',
            'otros' => '0.00', 'total' => '30900.00',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        DB::table('pago_electronico')->insert([
            'pedimento_id' => $pedId, 'patente' => '3434', 'aduana' => '470',
            'nombre_institucion' => 'BBVA Bancomer, S.A.',
            'linea_captura' => '1000 2141 4709 3434 90028',
            'importe_pagado' => '30900.00', 'fecha_pago' => '2024-09-18',
            'num_operacion_bancaria' => '5040011475', 'num_transaccion_sat' => null,
            'medio_presentacion' => 'OTROS MEDIOS ELECTRÓNICOS (PAGO ELECTRÓNICO)',
            'medio_recepcion_cobro' => 'EFECTIVO (CARGO A CUENTA)',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Partida 001
        $p1 = DB::table('partidas')->insertGetId([
            'pedimento_id' => $pedId, 'secuencia' => 1,
            'fraccion_arancelaria' => '84433291', 'subdivision' => '04',
            'vinculacion' => '0', 'metodo_valoracion' => 1,
            'pais_origen_destino' => 'GBR', 'pais_vendedor_comprador' => 'GBR',
            'umc' => '6', 'cantidad_umc' => '1.000',
            'precio_valor_comercial' => '78104.50000', 'precio_origen_destino' => '1',
            'descripcion' => 'IMPRESORA INDUSTRIAL DE INYECCION DE TINTA CONTINUA LINX TT750, NUEVA. N/S: TT750-24-0081',
            'val_aduana_usd' => '89746.00', 'importe_precio_pagado' => '78104.50',
            'precio_unitario' => '78104.50000', 'valor_agregado' => '0.00',
            'marca' => 'LINX', 'modelo' => 'TT750', 'codigo_producto' => '4900-3040-P100',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Partida 002
        DB::table('partidas')->insert([
            'pedimento_id' => $pedId, 'secuencia' => 2,
            'fraccion_arancelaria' => '84433291', 'subdivision' => '04',
            'vinculacion' => '0', 'metodo_valoracion' => 1,
            'pais_origen_destino' => 'GBR', 'pais_vendedor_comprador' => 'GBR',
            'umc' => '6', 'cantidad_umc' => '1.000',
            'precio_valor_comercial' => '78104.50000', 'precio_origen_destino' => '1',
            'descripcion' => 'IMPRESORA INDUSTRIAL DE INYECCION DE TINTA CONTINUA LINX TT750, NUEVA. N/S: TT750-24-0082',
            'val_aduana_usd' => '89746.00', 'importe_precio_pagado' => '78104.50',
            'precio_unitario' => '78104.50000', 'valor_agregado' => '0.00',
            'marca' => 'LINX', 'modelo' => 'TT750', 'codigo_producto' => '4900-3040-P100',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->command->info('✓ Seeder completado. Usuarios creados:');
        $this->command->table(
            ['Usuario', 'Contraseña', 'Rol'],
            [
                ['admin',   'admin123',   'ADMIN'],
                ['profesor','profesor123','PROFESOR'],
                ['alumno',  'alumno123',  'ALUMNO'],
            ]
        );
    }
}
