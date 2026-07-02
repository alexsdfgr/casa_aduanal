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

    protected static function booted()
    {
        static::saving(function ($contribucion) {
            if (is_null($contribucion->getAttribute('tasa'))) {
                $contribucion->setAttribute('tasa', 0);
            }
            if (is_null($contribucion->getAttribute('fp'))) {
                $contribucion->setAttribute('fp', 1);
            }
            if (is_null($contribucion->getAttribute('importe'))) {
                $contribucion->setAttribute('importe', 0);
            }
        });
    }

    // Mapear el atributo virtual 'con' a la columna real de la base de datos 'contribucion'
    public function setConAttribute($value)
    {
        $this->attributes['contribucion'] = $value;
    }

    public function getConAttribute()
    {
        return $this->attributes['contribucion'] ?? null;
    }

    // Mapear el atributo virtual 'tt' a la columna real de la base de datos 'tipo_tasa'
    public function setTtAttribute($value)
    {
        $this->attributes['tipo_tasa'] = $value;
    }

    public function getTtAttribute()
    {
        return $this->attributes['tipo_tasa'] ?? null;
    }

    public function partida()
    {
        return $this->belongsTo(Partida::class, 'partida_id');
    }
}