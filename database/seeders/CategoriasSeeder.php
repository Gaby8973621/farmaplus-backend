<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = ['Dolor', 'Vitaminas', 'Infantil', 'Belleza'];
        foreach ($categorias as $i => $nombre) {
            Categoria::firstOrCreate([
                'nombre' => $nombre,
                'slug' => strtolower($nombre),
                'orden' => $i + 1,
            ]);
        }
    }
}

