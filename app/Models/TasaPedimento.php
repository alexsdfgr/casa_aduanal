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

    public static $contribucionMapping = [
        'DTA'     => 1,
        'C.C.'    => 2,
        'IVA'     => 3,
        'ISAN'    => 4,
        'IGI/IGE' => 6,
        'REC.'    => 7,
        'OTROS'   => 9,
        'MULT.'   => 11,
        '2.5'     => 12,
        'RT'      => 13,
        'PRV'     => 15,
        'EUR'     => 16,
        'REU'     => 17,
        'MT'      => 20,
        'IEPS'    => 22,
        'IVA/PRV' => 23,
        '2IB'     => 24,
        '2IA2'    => 25,
        '2IA1'    => 26,
        '2IC'     => 27,
        '2IF'     => 28,
        '2IG'     => 29,
        '2IJ'     => 30,
        '2II'     => 31,
        'ICF'     => 32,
        'IEPSDIE' => 33,
        'ICNF'    => 34,
        'LIEPS'   => 35,
        'DFC'     => 50,
    ];

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