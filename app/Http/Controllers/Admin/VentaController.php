<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('ventas')
            ->join('users', 'ventas.idUsuario', '=', 'users.id')
            ->select('ventas.*', 'users.name as vendedor');

        if ($request->buscar) {
            $query->where('ventas.idVenta', 'LIKE', "%{$request->buscar}%");
        }

        if ($request->vendedor) {
            $query->where('ventas.idUsuario', $request->vendedor);
        }

        if ($request->fecha) {
            $query->whereDate('ventas.created_at', $request->fecha);
        }

        $ventas = $query->orderBy('ventas.created_at', 'desc')->paginate(10);
        $vendedores = DB::table('users')->where('activo', 1)->get();

        return view('admin.ventas.index', compact('ventas', 'vendedores'));
    }

    public function show($id)
    {
        $venta = DB::table('ventas')
            ->join('users', 'ventas.idUsuario', '=', 'users.id')
            ->select('ventas.*', 'users.name as vendedor', 'users.email as vendedor_email')
            ->where('ventas.idVenta', $id)
            ->first();

        if (!$venta) abort(404);

        $detalles = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->select('detalle_ventas.*', 'productos.nombre as producto')
            ->where('detalle_ventas.idVenta', $id)
            ->get();

        return view('admin.ventas.show', compact('venta', 'detalles'));
    }

    public function destroy($id)
    {
        $venta = DB::table('ventas')->where('idVenta', $id)->first();
        if (!$venta) abort(404);

        if ($venta->estado === 'Anulada') {
            return back()->withErrors(['error' => 'Esta venta ya está anulada']);
        }

        DB::beginTransaction();
        try {
            // Anular venta
            DB::table('ventas')->where('idVenta', $id)->update([
                'estado' => 'Anulada',
                'updated_at' => now(),
            ]);

            // Restaurar stock
            $detalles = DB::table('detalle_ventas')->where('idVenta', $id)->get();
            foreach ($detalles as $det) {
                DB::table('productos')
                    ->where('idProducto', $det->idProducto)
                    ->increment('stock_actual', $det->cantidad);
            }

            DB::commit();
            return redirect()->route('admin.ventas.index')->with('success', 'Venta anulada y stock restaurado');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}