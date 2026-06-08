<?php
// app/Models/ProveedorComprador.php
// Datos del proveedor del pedimento 4000478:
// GB880391804 - LINX PRINTING TECHNOLOGIES LTD - REINO UNIDO

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProveedorComprador extends Model
{
    protected $table = 'proveedores_compradores';

    protected $fillable = [
        'pedimento_id',
        'id_fiscal',    // GB880391804
        'nombre',       // LINX PRINTING TECHNOLOGIES LTD
        'domicilio',    // STOCKS BRIDGE WAY No. Ext. 8 ST. IVES CAMBRIDGESHIRE...
        'vinculacion',  // NO
    ];

    public function pedimento()
    {
        return $this->belongsTo(Pedimento::class, 'pedimento_id');
    }
}