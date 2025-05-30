<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Producto;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        $data = Producto::orderBy("orden")->get(["id", "nombre", "urlfoto", "precio", "stock"]);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'urlfoto' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'user_id' => 'required|exists:users,id',
            'orden' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = new Producto($request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'categoria_id', 'user_id', 'orden'
        ]));

        if ($request->urlfoto) {
            $data->urlfoto = self::guardarImagen($request->nombre, $request->urlfoto);
        }

        $data->save();
        return response()->json($data, 200);
    }

    public function show($id)
    {
        $data = Producto::find($id);

        if (!$data) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $data = Producto::find($id);

        if (!$data) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'urlfoto' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'user_id' => 'required|exists:users,id',
            'orden' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data->fill($request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'categoria_id', 'user_id', 'orden'
        ]));

        if ($request->urlfoto) {
            $data->urlfoto = self::guardarImagen($request->nombre, $request->urlfoto);
        }

        $data->save();
        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        if ($producto->urlfoto) {
            $path = public_path("/Img/producto/" . $producto->urlfoto);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $producto->delete();
        return response()->json("Producto eliminado correctamente", 200);
    }

    private static function guardarImagen($nombre, $img)
    {
        $folderPath = "/Img/producto/";
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $filename = Str::slug($nombre) . '.' . $image_type;
        $file = public_path($folderPath . $filename);
        file_put_contents($file, $image_base64);
        return $filename;
    }
}
