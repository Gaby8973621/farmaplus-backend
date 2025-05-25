<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /// Mostrar lista de categorías ordenadas ///
    public function index()
    {
        $data = Categoria::orderBy("orden")->get(["id", "nombre", "urlfoto"]);
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

        // Crear categoría
        $data = new Categoria();
        $data->nombre = $request->nombre;

        // Procesar imagen base64 si existe
        if ($request->urlfoto) {
            $img = $request->urlfoto;

            $folderPath = "/Img/categoria/";
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . Str::slug($request->nombre) . '.' . $image_type;
            file_put_contents(public_path($file), $image_base64);

            $data->urlfoto = Str::slug($request->nombre) . '.' . $image_type;
        }

        $data->slug = Str::slug($request->nombre);
        $data->save();

        return response()->json($data, 200);
    }

    /// Mostrar una categoría específica ///
    public function show($id)
    {
        $data = Categoria::find($id);

        if (!$data) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        return response()->json($data, 200);
    }

    /// Actualizar una categoría existente ///
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

        $data = Categoria::find($id);

        if (!$data) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;


        // Actualizar imagen si existe
        if ($request->urlfoto) {
            $img = $request->urlfoto;
            $folderPath = "/Img/categoria/";
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . Str::slug($request->nombre) . '.' . $image_type;
            file_put_contents(public_path($file), $image_base64);

            $data->urlfoto = Str::slug($request->nombre) . '.' . $image_type;
        }

        $data->slug = Str::slug($request->nombre);
        $data->save();

        return response()->json($data, 200);
    }

    //// Destroy para eliminar una categoria////
    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        // Si tiene imagen, podrías eliminarla también si es necesario
        if ($categoria->urlfoto) {
            $path = public_path("/Img/categoria/" . $categoria->urlfoto);
            if (file_exists($path)) {
                // eliminar el archivo de imagen
                unlink($path);
            }
        }

        $categoria->delete();

        return response()->json("Categoría eliminada correctamente", 200);
    }

}
