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
        $data = Producto::orderBy("orden")->get(["id", "nombre", "urlfoto"]);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'urlfoto' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = new Producto($request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'categoria_id', 'user_id'
        ]));

        if ($request->urlfoto) {
            $image = $request->urlfoto;
            $folderPath = public_path("/Img/producto/");
            if (!is_dir($folderPath)) mkdir($folderPath, 0777, true);
            $image_parts = explode(";base64,", $image);
            $image_type = explode("image/", $image_parts[0])[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = Str::slug($request->nombre) . '.' . $image_type;
            file_put_contents($folderPath . $filename, $image_base64);
            $data->urlfoto = $filename;
        }

        $data->orden = Producto::max('orden') + 1;
        $data->save();

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($producto, 200);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'categoria_id' => 'required|exists:categorias,id',
            'user_id' => 'required|exists:users,id',
            'urlfoto' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $producto->fill($request->only([
            'nombre', 'descripcion', 'precio', 'stock', 'categoria_id', 'user_id'
        ]));

        if ($request->urlfoto) {
            $image = $request->urlfoto;
            $folderPath = public_path("/Img/producto/");
            if (!is_dir($folderPath)) mkdir($folderPath, 0777, true);
            $image_parts = explode(";base64,", $image);
            $image_type = explode("image/", $image_parts[0])[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filename = Str::slug($request->nombre) . '.' . $image_type;
            file_put_contents($folderPath . $filename, $image_base64);
            $producto->urlfoto = $filename;
        }

        $producto->save();

        return response()->json($producto, 200);
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

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}
