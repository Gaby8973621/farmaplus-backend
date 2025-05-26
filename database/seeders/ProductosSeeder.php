<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;

class ProductosSeeder extends Seeder
{

    public function run(): void
    {
        $categoria = Categoria::first(); // Asume que ya hay categorías
        $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();

        Producto::create([
            'nombre' => 'Ibuprofeno 400mg',
            'descripcion' => 'Alivia el dolor y reduce la fiebre.',
            'precio' => 2.50,
            'stock' => 50,
            'urlfoto' => 'https://farmaciassimilaresmx.vtexassets.com/arquivos/ids/162109/1853.png',
            'categoria_id' => $categoria->id,
            'user_id' => $admin->id,
        ]);

        Producto::create([
            'nombre' => 'Paracetamol MK',
            'descripcion' => 'Antifebril y analgésico común.',
            'precio' => 3.00,
            'stock' => 70,
            'urlfoto' => 'https://www.plmconnection.com/plmservices/PharmaSearchEngine/Mexico/DEF/SIDEF/400x400/sanfer_algitrin_tabs_caja12.png',
            'categoria_id' => $categoria->id,
            'user_id' => $admin->id,
        ]);
    }
}
