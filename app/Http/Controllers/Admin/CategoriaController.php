<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = DB::table('categorias')
            ->leftJoin('productos', function ($join) {
                $join->on('categorias.idCategoria', '=', 'productos.idCategoria')
                     ->where('productos.activo', 1);
            })
            ->select(
                'categorias.*',
                DB::raw('COUNT(productos.idProducto) as total_productos')
            )
            ->groupBy('categorias.idCategoria', 'categorias.nombre', 'categorias.descripcion', 'categorias.activo', 'categorias.created_at', 'categorias.updated_at')
            ->orderBy('categorias.nombre')
            ->paginate(10);

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:255',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Esta categoría ya existe',
        ]);

        DB::table('categorias')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente');
    }

    public function show($id)
    {
        $categoria = DB::table('categorias')->where('idCategoria', $id)->first();
        if (!$categoria) abort(404);

        $productos = DB::table('productos')
            ->where('idCategoria', $id)
            ->where('activo', 1)
            ->paginate(10);

        return view('admin.categorias.show', compact('categoria', 'productos'));
    }

    public function edit($id)
    {
        $categoria = DB::table('categorias')->where('idCategoria', $id)->first();
        if (!$categoria) abort(404);

        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:categorias,nombre,' . $id . ',idCategoria',
            'descripcion' => 'nullable|string|max:255',
        ]);

        DB::table('categorias')->where('idCategoria', $id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->has('activo') ? 1 : 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente');
    }

    public function destroy($id)
    {
        // Verificar que no tenga productos
        $tieneProductos = DB::table('productos')->where('idCategoria', $id)->exists();

        if ($tieneProductos) {
            return back()->withErrors(['error' => 'No se puede eliminar: la categoría tiene productos asociados']);
        }

        DB::table('categorias')->where('idCategoria', $id)->delete();

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada correctamente');
    }
}