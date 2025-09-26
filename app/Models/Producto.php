<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = [
        'id',
        'id_categoria',
        'nombre',
        'precio',
        'api_id'
    ];

    public $timestamps = false;

    public function cajas()
    {
        return $this->belongsToMany(
            Caja::class,
            'caja_producto',
            'id_producto',
            'id_caja'
        );
    }
}
