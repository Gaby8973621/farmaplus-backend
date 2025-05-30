<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function index()
    {
        return response()->json(
            Venta::with('detalles.producto')->get(),
            200
        );
    }

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

        /// Validar stock y registrar la venta
        foreach ($request->productos as $prod) {
            $inventario = Inventario::where('producto_id', $prod['id'])->first();
            if (!$inventario || $inventario->stock < $prod['cantidad']) {
                return response()->json([
                    'error' => 'Stock insuficiente para el producto ID: ' . $prod['id']
                ], 400);
            }
        }

        ///para crear la enta
        $venta = Venta::create([
            'user_id' => $request->user_id,
            'total' => $request->total,
        ]);

        /// Guardar y actualizar inventario
        foreach ($request->productos as $prod) {
            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $prod['id'],
                'cantidad' => $prod['cantidad'],
                'precio_unitario' => $prod['precio'],
            ]);

            $inventario = Inventario::where('producto_id', $prod['id'])->first();
            if ($inventario) {
                $inventario->stock -= $prod['cantidad'];
                $inventario->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Venta registrada con éxito',
            'venta' => $venta->load('detalles.producto')
        ], 201);
    }

    public function show($id)
    {
        $venta = Venta::with('detalles.producto')->find($id);

        if (!$venta) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        return response()->json($venta, 200);
    }

    public function destroy($id)
    {
        $venta = Venta::find($id);

        if (!$venta) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $venta->detalles()->delete();
        $venta->delete();

        return response()->json(['message' => 'Venta eliminada correctamente'], 200);
    }
}
