<?php


namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        return Inventario::with('producto')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'stock' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $inventario = Inventario::create($validated);
        return response()->json($inventario, 201);
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::findOrFail($id);

        $validated = $request->validate([
            'stock' => 'integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $inventario->update($validated);
        return response()->json($inventario);
    }

    public function destroy($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->delete();

        return response()->json(['message' => 'Inventario eliminado']);
    }
}
