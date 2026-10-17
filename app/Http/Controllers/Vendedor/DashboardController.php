<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();

        // ============================================
        // ESTADÍSTICAS DEL VENDEDOR
        // ============================================

        // 1. Mis ventas del día
        $ventasHoy = DB::table('ventas')
            ->where('idUsuario', $usuario->id)
            ->whereDate('created_at', today())
            ->where('estado', 'Activa')
            ->count();

        $montoVentasHoy = DB::table('ventas')
            ->where('idUsuario', $usuario->id)
            ->whereDate('created_at', today())
            ->where('estado', 'Activa')
            ->sum('total');

        // 2. Mis ventas del mes
        $ventasMes = DB::table('ventas')
            ->where('idUsuario', $usuario->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('estado', 'Activa')
            ->count();

        $montoVentasMes = DB::table('ventas')
            ->where('idUsuario', $usuario->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('estado', 'Activa')
            ->sum('total');

        // 3. Total de productos disponibles
        $totalProductos = DB::table('productos')
            ->where('activo', 1)
            ->count();

        // 4. Productos con stock crítico
        $stockCritico = DB::table('productos')
            ->where('activo', 1)
            ->whereColumn('stock_actual', '<', 'stock_minimo')
            ->count();

        // ============================================
        // MIS ÚLTIMAS VENTAS
        // ============================================
        $ultimasVentas = DB::table('ventas')
            ->where('idUsuario', $usuario->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ============================================
        // PRODUCTOS MÁS VENDIDOS POR MÍ
        // ============================================
        $productosVendidos = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.idVenta', '=', 'ventas.idVenta')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->where('ventas.idUsuario', $usuario->id)
            ->where('ventas.estado', 'Activa')
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle_ventas.cantidad) as total_vendido')
            )
            ->groupBy('productos.idProducto', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        return view('vendedor.dashboard', compact(
            'usuario',
            'ventasHoy',
            'montoVentasHoy',
            'ventasMes',
            'montoVentasMes',
            'totalProductos',
            'stockCritico',
            'ultimasVentas',
            'productosVendidos'
        ));
    }
}