<?php
// app/Models/PagoElectronico.php
// Pago del pedimento 4000478:
// BBVA Bancomer / Línea: 0324 03UT NJP1 4341 8296
// Importe: $30,900 / Fecha: 02/09/2024

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoElectronico extends Model
{
    protected $table = 'pago_electronico';

    protected $fillable = [
        'pedimento_id',
        'patente',               // 3434
        'aduana',                // 470
        'nombre_institucion',    // BBVA Bancomer, S.A.
        'linea_captura',         // 0324 03UT NJP1 4341 8296
        'importe_pagado',        // 30900
        'fecha_pago',            // 02/09/2024
        'num_operacion_bancaria',// 01224246561830
        'num_transaccion_sat',   // 40012020920241555361
        'medio_presentacion',    // OTROS MEDIOS ELECTRÓNICOS (PAGO ELECTRÓNICO)
        'medio_recepcion_cobro', // EFECTIVO (CARGO A CUENTA)
    ];

    protected $casts = [
        'fecha_pago' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($pago) {
            if (is_null($pago->getAttribute('importe_pagado'))) {
                $pago->setAttribute('importe_pagado', 0);
            }
        });
    }

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}