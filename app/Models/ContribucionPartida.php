<?php
// app/Models/ContribucionPartida.php
// Contribuciones por partida del pedimento 4000478:
// Partida 001: IGI=0 / IVA=28948
// Partida 002: IGI=40 / IVA=134

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContribucionPartida extends Model
{
    protected $table = 'contribuciones_partida';

    protected $fillable = [
        'partida_id',
        'con', // IGI / IVA
        'tt',           // 1 (ad valorem)
        'tasa',         // 0.00000 / 16.00000 / 5.00000
        'fp',           // 0, 1
        'importe',      // 0, 28948, 40, 134
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
}