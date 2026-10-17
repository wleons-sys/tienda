<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->select('productos.*', 'categorias.nombre as categoria_nombre');

        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('productos.nombre', 'LIKE', "%{$request->buscar}%")
                  ->orWhere('productos.codigo_interno', 'LIKE', "%{$request->buscar}%");
            });
        }

        if ($request->categoria) {
            $query->where('productos.idCategoria', $request->categoria);
        }

        if ($request->estado === 'activos') {
            $query->where('productos.activo', 1);
        } elseif ($request->estado === 'inactivos') {
            $query->where('productos.activo', 0);
        } elseif ($request->estado === 'criticos') {
            $query->whereColumn('productos.stock_actual', '<', 'productos.stock_minimo')
                  ->where('productos.activo', 1);
        }

        $productos = $query->orderBy('productos.created_at', 'desc')->paginate(10);
        $categorias = DB::table('categorias')->where('activo', 1)->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = DB::table('categorias')->where('activo', 1)->get();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'idCategoria' => 'required|exists:categorias,idCategoria',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'codigo_interno' => 'nullable|string|max:50|unique:productos,codigo_interno',
        ]);

        DB::table('productos')->insert([
            'nombre' => $request->nombre,
            'idCategoria' => $request->idCategoria,
            'precio' => $request->precio,
            'stock_actual' => $request->stock_actual,
            'stock_minimo' => $request->stock_minimo,
            'codigo_interno' => $request->codigo_interno,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente');
    }

    public function show($id)
    {
        $producto = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->select('productos.*', 'categorias.nombre as categoria_nombre')
            ->where('productos.idProducto', $id)
            ->first();

        if (!$producto) abort(404);

        return view('admin.productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = DB::table('productos')->where('idProducto', $id)->first();
        if (!$producto) abort(404);

        $categorias = DB::table('categorias')->where('activo', 1)->get();

        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'idCategoria' => 'required|exists:categorias,idCategoria',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'codigo_interno' => 'nullable|string|max:50|unique:productos,codigo_interno,' . $id . ',idProducto',
        ]);

        DB::table('productos')->where('idProducto', $id)->update([
            'nombre' => $request->nombre,
            'idCategoria' => $request->idCategoria,
            'precio' => $request->precio,
            'stock_actual' => $request->stock_actual,
            'stock_minimo' => $request->stock_minimo,
            'codigo_interno' => $request->codigo_interno,
            'activo' => $request->has('activo') ? 1 : 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        // Baja lógica
        DB::table('productos')->where('idProducto', $id)->update([
            'activo' => 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.productos.index')->with('success', 'Producto dado de baja correctamente');
    }
}