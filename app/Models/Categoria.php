<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ['nombre', 'orden', 'urlfoto'];
    public $timestamps = false;

    public function producto()
    {
        return $this->hasMany(Producto::class);
    }



}
