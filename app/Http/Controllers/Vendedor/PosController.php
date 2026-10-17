<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Mostrar el Punto de Venta
     */
    public function index()
    {
        $productos = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->where('productos.activo', 1)
            ->where('productos.stock_actual', '>', 0)
            ->select(
                'productos.idProducto',
                'productos.nombre',
                'productos.precio',
                'productos.stock_actual',
                'productos.codigo_interno',
                'categorias.nombre as categoria'
            )
            ->orderBy('productos.nombre')
            ->get();

        return view('vendedor.pos.index', compact('productos'));
    }

    /**
     * Buscar productos por nombre o código (AJAX)
     */
    public function buscar(Request $request)
    {
        $buscar = $request->input('q', '');

        if (strlen($buscar) < 2) {
            return response()->json([]);
        }

        $productos = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->where('productos.activo', 1)
            ->where('productos.stock_actual', '>', 0)
            ->where(function ($query) use ($buscar) {
                $query->where('productos.nombre', 'LIKE', "%{$buscar}%")
                      ->orWhere('productos.codigo_interno', 'LIKE', "%{$buscar}%");
            })
            ->select(
                'productos.idProducto',
                'productos.nombre',
                'productos.precio',
                'productos.stock_actual',
                'productos.codigo_interno',
                'categorias.nombre as categoria'
            )
            ->limit(10)
            ->get();

        return response()->json($productos);
    }

    /**
     * Procesar la venta
     */
    public function procesar(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.idProducto' => 'required|exists:productos,idProducto',
            'items.*.cantidad' => 'required|integer|min:1',
            'descuento' => 'nullable|numeric|min:0',
        ], [
            'items.required' => 'Debes agregar al menos un producto',
            'items.*.cantidad.min' => 'La cantidad debe ser mayor a 0',
        ]);

        DB::beginTransaction();

        try {
            $items = $request->input('items');
            $descuento = $request->input('descuento', 0);

            $subtotal = 0;
            $detalles = [];

            // Validar stock de cada producto
            foreach ($items as $item) {
                $producto = DB::table('productos')->where('idProducto', $item['idProducto'])->first();

                if (!$producto) {
                    throw new \Exception("Producto no encontrado");
                }

                if ($producto->stock_actual < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para: {$producto->nombre}. Disponible: {$producto->stock_actual}");
                }

                $totalLinea = $producto->precio * $item['cantidad'];
                $subtotal += $totalLinea;

                $detalles[] = [
                    'idProducto' => $producto->idProducto,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'descuento' => 0,
                    'total_linea' => $totalLinea,
                    'nombre' => $producto->nombre,
                ];
            }

            $total = $subtotal - $descuento;

            // Registrar venta
            $idVenta = DB::table('ventas')->insertGetId([
                'idUsuario' => auth()->id(),
                'fecha' => now(),
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'total' => $total,
                'estado' => 'Activa',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Registrar detalles y actualizar stock
            foreach ($detalles as $detalle) {
                DB::table('detalle_ventas')->insert([
                    'idVenta' => $idVenta,
                    'idProducto' => $detalle['idProducto'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'],
                    'total_linea' => $detalle['total_linea'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Actualizar stock
                $producto = DB::table('productos')->where('idProducto', $detalle['idProducto'])->first();
                $stockAnterior = $producto->stock_actual;
                $stockNuevo = $stockAnterior - $detalle['cantidad'];

                DB::table('productos')->where('idProducto', $detalle['idProducto'])->update([
                    'stock_actual' => $stockNuevo,
                    'updated_at' => now(),
                ]);

                // Registrar movimiento
                DB::table('movimientos_inventario')->insert([
                    'idProducto' => $detalle['idProducto'],
                    'idUsuario' => auth()->id(),
                    'tipo' => 'Salida',
                    'cantidad' => -$detalle['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockNuevo,
                    'referencia' => 'Venta #' . str_pad($idVenta, 4, '0', STR_PAD_LEFT),
                    'anulado' => 0,
                    'created_at' => now(),
                ]);
            }

            // Registrar auditoría
            DB::table('logs_auditoria')->insert([
                'idUsuario' => auth()->id(),
                'accion' => 'Venta creada',
                'tabla_afectada' => 'ventas',
                'registro_afectado_id' => $idVenta,
                'descripcion' => 'Venta por Q' . number_format($total, 2) . ' con ' . count($detalles) . ' productos',
                'ip_usuario' => $request->ip(),
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'idVenta' => $idVenta,
                'total' => $total,
                'mensaje' => 'Venta registrada correctamente',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => $e->getMessage(),
            ], 422);
        }
    }
}