<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Inventario;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            Inventario::create([
                'producto_id' => $producto->id,
                // stock aleatorio
                'stock' => rand(10, 20),
                'ubicacion' => 'Sucursal Central',
            ]);
        }
    }
}
