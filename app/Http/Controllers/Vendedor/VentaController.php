<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('ventas')
            ->where('idUsuario', auth()->id());

        if ($request->fecha) {
            $query->whereDate('created_at', $request->fecha);
        }

        $ventas = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $stats = [
            'hoy' => DB::table('ventas')
                ->where('idUsuario', auth()->id())
                ->whereDate('created_at', today())
                ->where('estado', 'Activa')
                ->count(),
            'mes' => DB::table('ventas')
                ->where('idUsuario', auth()->id())
                ->whereMonth('created_at', now()->month)
                ->where('estado', 'Activa')
                ->count(),
            'monto_mes' => DB::table('ventas')
                ->where('idUsuario', auth()->id())
                ->whereMonth('created_at', now()->month)
                ->where('estado', 'Activa')
                ->sum('total'),
        ];

        return view('vendedor.ventas.index', compact('ventas', 'stats'));
    }

    public function show($id)
    {
        $venta = DB::table('ventas')
            ->where('idVenta', $id)
            ->where('idUsuario', auth()->id())
            ->first();

        if (!$venta) abort(404);

        $detalles = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->select('detalle_ventas.*', 'productos.nombre as producto')
            ->where('detalle_ventas.idVenta', $id)
            ->get();

        return view('vendedor.ventas.show', compact('venta', 'detalles'));
    }
}