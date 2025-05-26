<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Producto;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /// Mostrar lista de Productos ordenadas ///
    public function index()
    {
        $data = Producto::orderBy("orden")->get(["id", "nombre", "urlfoto"]);
        return response()->json($data, 200);
    }

    /// Guardar una nueva categoría ///
    public function store(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'urlfoto' => 'nullable|string', // imagen base64 opcional
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear Producto
        $data = new Producto();
        $data->nombre = $request->nombre;

        // Procesar imagen base64 si existe
        if ($request->urlfoto) {
            $img = $request->urlfoto;

            $folderPath = "/Img/producto/";
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . Str::slug($request->nombre) . '.' . $image_type;
            file_put_contents(public_path($file), $image_base64);

            $data->urlfoto = Str::slug($request->nombre) . '.' . $image_type;
        }


        $data->save();

        return response()->json($data, 200);
    }

    /// Mostrar una Producto específica ///
    public function show($id)
    {
        $data = Producto::find($id);

        if (!$data) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($data, 200);
    }

    /// Actualizar una Producto existente ///
    public function update(Request $request, $id)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'urlfoto' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Producto::find($id);

        if (!$data) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;


        // Actualizar imagen si existe
        if ($request->urlfoto) {
            $img = $request->urlfoto;
            $folderPath = "/Img/producto/";
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . Str::slug($request->nombre) . '.' . $image_type;
            file_put_contents(public_path($file), $image_base64);

            $data->urlfoto = Str::slug($request->nombre) . '.' . $image_type;
        }

        $data->save();

        return response()->json($data, 200);
    }

    //// Destroy para eliminar una Producto////
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Si tiene imagen, podrías eliminarla también si es necesario
        if ($producto->urlfoto) {
            $path = public_path("/Img/producto/" . $producto->urlfoto);
            if (file_exists($path)) {
                // eliminar el archivo de imagen
                unlink($path);
            }
        }

        $producto->delete();

        return response()->json("Producto eliminado correctamente", 200);
    }
}
