<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Producto;

class CarritoApiController extends Controller
{
    public function agregar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $carrito = Carrito::create([
            'user_id' => $request->user()->id,
            'producto_id' => $producto->id,
            'cantidad' => $request->cantidad
        ]);

        return response()->json($carrito);
    }

    public function mostrar(Request $request)
    {
        return Carrito::with('producto')->where('user_id', $request->user()->id)->get();
    }

    public function eliminar($id)
    {
        $item = Carrito::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Producto eliminado del carrito']);
    }
}
