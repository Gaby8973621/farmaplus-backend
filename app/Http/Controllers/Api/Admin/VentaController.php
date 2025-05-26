<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    // Mostrar todas las ventas con sus detalles
    public function index()
    {
        return Venta::with('detalles.producto')->get();
    }

    // Registrar una nueva venta
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'total' => 'required|numeric|min:0',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $venta = Venta::create([
            'user_id' => $request->user_id,
            'total' => $request->total,
        ]);

        foreach ($request->productos as $prod) {
            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $prod['id'],
                'cantidad' => $prod['cantidad'],
                'precio_unitario' => $prod['precio'],
            ]);
        }

        return response()->json([
            'message' => 'Venta registrada con éxito',
            'venta' => $venta->load('detalles.producto')
        ]);
    }

    // Mostrar una venta específica
    public function show($id)
    {
        return Venta::with('detalles.producto')->findOrFail($id);
    }

    // Eliminar una venta
    public function destroy($id)
    {
        Venta::destroy($id);
        return response()->json(['message' => 'Venta eliminada']);
    }
}
