<?php
// app/Models/AgenteAduanal.php
// Agente del pedimento 4000478:
// LYDIA GEORGINA ARELLANO VILLAMIL - GEORGINA ARELLANO SC
// RFC: GAR0103133K0 / CURP: AEVL520423MDFRLY02 / Patente: 3434

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgenteAduanal extends Model
{
    protected $table = 'agentes_aduanales';

    protected $fillable = [
        'pedimento_id',
        'nombre',                // LYDIA GEORGINA ARELLANO VILLAMIL
        'razon_social',          // GEORGINA ARELLANO SC
        'rfc',                   // GAR0103133K0
        'curp',                  // AEVL520423MDFRLY02
        'patente',               // 3434
        'num_serie_certificado', // 00001000000518683292
        'efirma',                // N0CDRe1Hjm81w+xCK...
    ];

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}