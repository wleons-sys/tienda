<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('movimientos_inventario')
            ->join('productos', 'movimientos_inventario.idProducto', '=', 'productos.idProducto')
            ->join('users', 'movimientos_inventario.idUsuario', '=', 'users.id')
            ->select(
                'movimientos_inventario.*',
                'productos.nombre as producto_nombre',
                'productos.codigo_interno',
                'users.name as usuario_nombre'
            );

        // Filtros
        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('productos.nombre', 'LIKE', "%{$request->buscar}%")
                  ->orWhere('productos.codigo_interno', 'LIKE', "%{$request->buscar}%");
            });
        }

        if ($request->tipo) {
            $query->where('movimientos_inventario.tipo', $request->tipo);
        }

        if ($request->fecha) {
            $query->whereDate('movimientos_inventario.created_at', $request->fecha);
        }

        $movimientos = $query->orderBy('movimientos_inventario.created_at', 'desc')
                             ->paginate(15);

        // Stats
        $stats = [
            'total' => DB::table('movimientos_inventario')->count(),
            'entradas' => DB::table('movimientos_inventario')->where('tipo', 'Entrada')->count(),
            'salidas' => DB::table('movimientos_inventario')->where('tipo', 'Salida')->count(),
            'ajustes' => DB::table('movimientos_inventario')->where('tipo', 'Ajuste')->count(),
        ];

        $productos = DB::table('productos')->where('activo', 1)->orderBy('nombre')->get();

        return view('admin.movimientos.index', compact('movimientos', 'stats', 'productos'));
    }

    public function create()
    {
        $productos = DB::table('productos')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return view('admin.movimientos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idProducto' => 'required|exists:productos,idProducto',
            'tipo' => 'required|in:Entrada,Salida,Ajuste',
            'cantidad' => 'required|integer|min:1',
            'referencia' => 'nullable|string|max:255',
        ], [
            'idProducto.required' => 'Selecciona un producto',
            'tipo.required' => 'Selecciona el tipo de movimiento',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
        ]);

        $producto = DB::table('productos')->where('idProducto', $request->idProducto)->first();
        if (!$producto) {
            return back()->withErrors(['error' => 'Producto no encontrado']);
        }

        // Validar stock para salidas
        if ($request->tipo === 'Salida' && $producto->stock_actual < $request->cantidad) {
            return back()->withErrors([
                'error' => "Stock insuficiente. Disponible: {$producto->stock_actual} unidades"
            ])->withInput();
        }

        // Calcular nuevo stock
        $stockAnterior = $producto->stock_actual;
        $stockNuevo = $stockAnterior;

        if ($request->tipo === 'Entrada') {
            $stockNuevo = $stockAnterior + $request->cantidad;
        } elseif ($request->tipo === 'Salida') {
            $stockNuevo = $stockAnterior - $request->cantidad;
        } else { // Ajuste
            $stockNuevo = $request->cantidad;
        }

        DB::beginTransaction();
        try {
            // Registrar movimiento
            DB::table('movimientos_inventario')->insert([
                'idProducto' => $request->idProducto,
                'idUsuario' => auth()->id(),
                'tipo' => $request->tipo,
                'cantidad' => $request->tipo === 'Ajuste' ? ($stockNuevo - $stockAnterior) : $request->cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'referencia' => $request->referencia,
                'anulado' => 0,
                'created_at' => now(),
            ]);

            // Actualizar stock del producto
            DB::table('productos')
                ->where('idProducto', $request->idProducto)
                ->update([
                    'stock_actual' => $stockNuevo,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return redirect()->route('admin.movimientos.index')
                ->with('success', 'Movimiento registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $movimiento = DB::table('movimientos_inventario')
            ->join('productos', 'movimientos_inventario.idProducto', '=', 'productos.idProducto')
            ->join('users', 'movimientos_inventario.idUsuario', '=', 'users.id')
            ->select(
                'movimientos_inventario.*',
                'productos.nombre as producto_nombre',
                'productos.codigo_interno',
                'productos.stock_actual as producto_stock_actual',
                'users.name as usuario_nombre',
                'users.email as usuario_email'
            )
            ->where('movimientos_inventario.idMovimiento', $id)
            ->first();

        if (!$movimiento) abort(404);

        return view('admin.movimientos.show', compact('movimiento'));
    }

    public function destroy($id)
    {
        $movimiento = DB::table('movimientos_inventario')->where('idMovimiento', $id)->first();
        if (!$movimiento) abort(404);

        if ($movimiento->anulado) {
            return back()->withErrors(['error' => 'Este movimiento ya está anulado']);
        }

        DB::beginTransaction();
        try {
            // Restaurar stock
            $producto = DB::table('productos')->where('idProducto', $movimiento->idProducto)->first();

            DB::table('productos')
                ->where('idProducto', $movimiento->idProducto)
                ->update([
                    'stock_actual' => $movimiento->stock_anterior,
                    'updated_at' => now(),
                ]);

            // Marcar como anulado
            DB::table('movimientos_inventario')
                ->where('idMovimiento', $id)
                ->update(['anulado' => 1]);

            DB::commit();

            return redirect()->route('admin.movimientos.index')
                ->with('success', 'Movimiento anulado y stock restaurado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}