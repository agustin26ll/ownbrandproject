<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = "caja";

    protected $fillable = [
        'id',
        'id_usuario',
        'id_envio',
        'nombre',
        'fecha_creacion'
    ];

    public $timestamps = false;

    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,
            'caja_producto',
            'id_caja',
            'id_producto'
        );
    }

    public function envio()
    {
        return $this->belongsTo(Envio::class, 'id_envio');
    }
}