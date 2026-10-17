<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->where('productos.activo', 1)
            ->select(
                'productos.*',
                'categorias.nombre as categoria_nombre'
            );

        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('productos.nombre', 'LIKE', "%{$request->buscar}%")
                  ->orWhere('productos.codigo_interno', 'LIKE', "%{$request->buscar}%");
            });
        }

        if ($request->categoria) {
            $query->where('productos.idCategoria', $request->categoria);
        }

        $productos = $query->orderBy('productos.nombre')->paginate(12);
        $categorias = DB::table('categorias')->where('activo', 1)->get();

        return view('vendedor.productos.index', compact('productos', 'categorias'));
    }
}