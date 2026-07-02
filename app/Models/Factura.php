<?php
// app/Models/Factura.php
// Factura del pedimento 4000478:
// COVE247092RQ4 / 2046361 - 23/08/2024 - FCA - USD 8,003.56

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'pedimento_id',
        'num_cfdi',           // COVE247092RQ4
        'num_factura',        // 2046361
        'fecha',              // 23/08/2024
        'incoterm',           // FCA
        'moneda_factura',     // USD
        'val_moneda_fact',    // 8,003.56
        'factor_moneda',      // 1.00000000
        'val_dolares',        // 8,003.56
        'no_guia_embarque',   // 139-40020341
        'id_embarque',        // M 136557 H
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($factura) {
            if (is_null($factura->getAttribute('val_moneda_fact'))) {
                $factura->setAttribute('val_moneda_fact', 0);
            }
            if (is_null($factura->getAttribute('factor_moneda'))) {
                $factura->setAttribute('factor_moneda', 1);
            }
            if (is_null($factura->getAttribute('val_dolares'))) {
                $factura->setAttribute('val_dolares', 0);
            }
        });
    }

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}