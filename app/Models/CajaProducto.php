<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaProducto extends Model
{
    protected $table = 'caja_producto';

    protected $fillable = [
        'id_caja',
        'id_producto'
    ];
    
    public $timestamps = false;

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
