<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = ['producto_id', 'stock', 'ubicacion'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}

