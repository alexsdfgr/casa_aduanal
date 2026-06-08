<?php
// app/Models/IdentificadorPartida.php
// Identificadores por partida del pedimento 4000478:
// Partida 001: UM-O / ES-N / EN-ENOM NOM-024-SCFI-2013...
// Partida 002: UM-O / ES-N / DH-NO APLICA

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentificadorPartida extends Model
{
    protected $table = 'identificadores_partida';

    protected $fillable = [
        'partida_id',
        'clave',        // UM / ES / EN / DH
        'c1',           // O / N / ENOM NOM-024-SCFI-2013 / NO APLICA
        'c2',           // E / 1.2 / etc
        'c3',
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
}