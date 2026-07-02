<?php
// app/Models/Partida.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas';

    protected $fillable = [
        'pedimento_id',
        'sec',              // antes: 'secuencia'
        'fraccion',         // antes: 'fraccion_arancelaria'
        'subd_nico',        // antes: 'subdivision'
        'vinc',             // antes: 'vinculacion'
        'met_val',          // antes: 'metodo_valoracion'
        'p_od',             // antes: 'pais_origen_destino'
        'p_vc',             // antes: 'pais_vendedor_comprador'
        'umc',
        'cantidad_umc',
        'umt',
        'cantidad_umt',
        'descripcion',
        'val_adu_usd',      // antes: 'val_aduana_usd'
        'imp_precio_pag',   // antes: 'importe_precio_pagado'
        'precio_unit',      // antes: 'precio_unitario'
        'val_agreg',        // antes: 'valor_agregado'
        'marca',
        'modelo',
        'codigo_producto',
        'obs_partida',      // antes: 'observaciones'
    ];

    protected static function booted()
    {
        static::saving(function ($partida) {
            $fieldsWithZero = [
                'cantidad_umc',
                'precio_valor_comercial',
                'val_adu_usd',
                'imp_precio_pag',
                'precio_unit',
                'val_agreg',
            ];
            foreach ($fieldsWithZero as $field) {
                if (is_null($partida->getAttribute($field))) {
                    $partida->setAttribute($field, 0);
                }
            }
            if (is_null($partida->getAttribute('sec'))) {
                $partida->setAttribute('sec', 1);
            }
            if (is_null($partida->getAttribute('vinc'))) {
                $partida->setAttribute('vinc', '0');
            }
            if (is_null($partida->getAttribute('met_val'))) {
                $partida->setAttribute('met_val', 1);
            }
        });
    }

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }

    public function contribuciones()
    {
        return $this->hasMany(ContribucionPartida::class, 'partida_id');
    }

    public function identificadores()
    {
        return $this->hasMany(IdentificadorPartida::class, 'partida_id');
    }
}