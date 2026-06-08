<?php
// app/Models/TasaPedimento.php
// Tasas del pedimento 4000478:
// 1-DTA / tasa 7 / 8.000
// 15-PRV / tasa 2 / 290.000
// 23-IVA/PRV / tasa 1 / 16.000

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TasaPedimento extends Model
{
    protected $table = 'tasas_pedimento';

    protected $fillable = [
        'pedimento_id',
        'contribucion',         // 1, 15, 23
        'nombre_contribucion',  // DTA, PRV, IVA/PRV
        'cve_tipo_tasa',        // 7, 2, 1
        'tasa',                 // 8.000, 290.000, 16.000
    ];

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}