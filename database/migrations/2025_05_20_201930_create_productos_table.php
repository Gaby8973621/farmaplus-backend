<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class ProductoApiController extends Controller
{
    public function index()
    {
        return Producto::all();
    }

    public function show($id)
    {
        return Producto::findOrFail($id);
    }
}
