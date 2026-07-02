<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = [
        'nombre',
        'profesor_id',
    ];

    public function profesor()
    {
        return $this->belongsTo(Usuario::class, 'profesor_id');
    }
}
