<?php
// app/Models/IdentificadorPedimento.php
// Identificadores del pedimento 4000478:
// CR-279 / ED-043824111IX74 / ED-0192240XUL1Y3

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentificadorPedimento extends Model
{
    protected $table = 'identificadores_pedimento';

    protected $fillable = [
        'pedimento_id',
        'clave',        // CR / ED
        'complemento1', // 279 / 043824111IX74
        'complemento2',
        'complemento3',
    ];

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}