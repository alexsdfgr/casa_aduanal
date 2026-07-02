<?php
// app/Models/CuadroLiquidacion.php
// Cuadro de liquidación del pedimento 4000478:
// DTA=1442 / PRV=290 / IVA/PRV=46 / IVA=29082 / IGI/IGE=40
// EFECTIVO=30,900 / OTROS=0 / TOTAL=30,900

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuadroLiquidacion extends Model
{
    protected $table = 'cuadro_liquidacion';

    protected $fillable = [
        'pedimento_id',
        'concepto_izq',  // DTA
        'fp_izq',        // 0
        'importe_izq',   // 1442
        'concepto_der',  // PRV
        'fp_der',        // 0
        'importe_der',   // 290
        'efectivo',      // 30900
        'otros',         // 0
        'total',         // 30900
    ];

    protected static function booted()
    {
        static::saving(function ($cuadro) {
            $fields = [
                'fp_izq',
                'importe_izq',
                'fp_der',
                'importe_der',
                'efectivo',
                'otros',
                'total',
            ];
            foreach ($fields as $field) {
                if (is_null($cuadro->getAttribute($field))) {
                    $cuadro->setAttribute($field, 0);
                }
            }
        });
    }

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}