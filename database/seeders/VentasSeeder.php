<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\User;
use App\Models\Producto;

class VentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = User::first();
        $producto1 = Producto::first();
        $producto2 = Producto::skip(1)->first();

        $venta = Venta::create([
            'user_id' => $usuario->id,
            'total' => ($producto1->precio + $producto2->precio),
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto1->id,
            'cantidad' => 1,
            'precio_unitario' => $producto1->precio,
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto2->id,
            'cantidad' => 1,
            'precio_unitario' => $producto2->precio,
        ]);
    }
}
