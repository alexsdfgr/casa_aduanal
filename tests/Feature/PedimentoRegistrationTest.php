<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedimentoRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_pedimento_with_null_incrementables(): void
    {
        // 1. Create a user
        $user = Usuario::create([
            'nombre' => 'Test Alumno',
            'username' => 'test_alumno',
            'password' => bcrypt('password123'),
            'rol' => 'ALUMNO',
            'activo' => true,
        ]);

        $this->actingAs($user);

        // 2. Submit the form with null/empty fields
        $response = $this->post(route('pedimentos.store'), [
            'pedimento' => [
                'num_pedimento' => '26 05 1465 987413',
                'tipo_operacion' => 'IMP',
                'cve_pedimento' => 'BO',
                'regimen' => 'ITR',
                'destino_origen' => '1',
                'tipo_cambio' => '20',
                'peso_bruto' => '52',
                'aduana_entrada_salida' => '010',
                'transporte_entrada_salida' => '4',
                'transporte_arribo' => '9',
                'transporte_salida' => '3',
                'rfc_importador' => '4562136587954',
                'curp_importador' => 'BUPY240705MMCNLLA2',
                'nombre_importador' => 'SEDFRGTHJKLM,Ñ',
                'domicilio_importador' => 'DFTGHYJUKL,Ñ',
                'valor_dolares' => '0',
                'valor_aduana' => '520000',
                'precio_pagado_valor_comercial' => '0',
                'val_seguros' => '20000',
                'seguros' => '500000',
                'fletes' => null, // empty/null
                'embalajes' => null,
                'otros_incrementables' => null,
                'transporte_decrementables' => null,
                'seguro_decrementables' => null,
                'carga_decrementables' => null,
                'descarga_decrementables' => null,
                'otros_decrementables' => null,
                'fecha_entrada' => '2026-07-01',
                'fecha_pago' => '2026-06-29',
                'clave_seccion_aduanera' => '010',
                'nombre_aduana_despacho' => 'TIJUANA',
            ]
        ]);

        $response->assertRedirect(route('pedimentos.index'));
        $this->assertDatabaseHas('pedimentos', [
            'num_pedimento' => '26 05 1465 987413',
            'fletes' => 0,
        ]);
    }
}
