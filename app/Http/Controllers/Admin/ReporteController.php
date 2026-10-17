<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Panel principal de reportes
     */
    public function index()
    {
        $stats = [
            'total_ventas' => DB::table('ventas')->where('estado', 'Activa')->count(),
            'monto_total' => DB::table('ventas')->where('estado', 'Activa')->sum('total'),
            'total_productos' => DB::table('productos')->where('activo', 1)->count(),
            'total_usuarios' => DB::table('users')->count(),
            'ventas_hoy' => DB::table('ventas')->whereDate('created_at', today())->where('estado', 'Activa')->count(),
            'monto_hoy' => DB::table('ventas')->whereDate('created_at', today())->where('estado', 'Activa')->sum('total'),
            'stock_critico' => DB::table('productos')
                ->where('activo', 1)
                ->whereColumn('stock_actual', '<', 'stock_minimo')
                ->count(),
        ];

        return view('admin.reportes.index', compact('stats'));
    }

    /**
     * Reporte: Ventas por fecha (con gráfica)
     */
    public function ventasPorFecha(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        // Ventas detalladas
        $ventas = DB::table('ventas')
            ->join('users', 'ventas.idUsuario', '=', 'users.id')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select('ventas.*', 'users.name as vendedor')
            ->orderBy('ventas.created_at', 'desc')
            ->get();

        // Datos para gráfica (por día)
        $ventasPorDia = DB::table('ventas')
            ->whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('estado', 'Activa')
            ->select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as monto')
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Datos para gráfica (por vendedor)
        $ventasPorVendedor = DB::table('ventas')
            ->join('users', 'ventas.idUsuario', '=', 'users.id')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select(
                'users.name as vendedor',
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as monto')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('monto', 'desc')
            ->get();

        $totalVentas = $ventas->count();
        $montoTotal = $ventas->sum('total');
        $dias = max(1, \Carbon\Carbon::parse($fechaInicio)->diffInDays($fechaFin) + 1);
        $promedioDiario = $montoTotal / $dias;

        return view('admin.reportes.ventas-fecha', compact(
            'ventas', 'ventasPorDia', 'ventasPorVendedor',
            'fechaInicio', 'fechaFin',
            'totalVentas', 'montoTotal', 'promedioDiario'
        ));
    }

    /**
     * Reporte: Ventas por categoría
     */
    public function ventasPorCategoria(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $categorias = DB::table('ventas')
            ->join('detalle_ventas', 'ventas.idVenta', '=', 'detalle_ventas.idVenta')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select(
                'categorias.idCategoria',
                'categorias.nombre',
                DB::raw('COUNT(DISTINCT ventas.idVenta) as total_ventas'),
                DB::raw('SUM(detalle_ventas.cantidad) as unidades_vendidas'),
                DB::raw('SUM(detalle_ventas.total_linea) as monto_total')
            )
            ->groupBy('categorias.idCategoria', 'categorias.nombre')
            ->orderBy('monto_total', 'desc')
            ->get();

        // Productos por categoría
        $productosPorCategoria = DB::table('categorias')
            ->leftJoin('productos', function($join) {
                $join->on('categorias.idCategoria', '=', 'productos.idCategoria')
                     ->where('productos.activo', '=', 1);
            })
            ->select(
                'categorias.nombre',
                DB::raw('COUNT(productos.idProducto) as total_productos'),
                DB::raw('COALESCE(SUM(productos.stock_actual), 0) as total_stock')
            )
            ->groupBy('categorias.idCategoria', 'categorias.nombre')
            ->get();

        return view('admin.reportes.ventas-categoria', compact(
            'categorias', 'productosPorCategoria', 'fechaInicio', 'fechaFin'
        ));
    }

    /**
     * Reporte: Productos más vendidos
     */
    public function productosMasVendidos(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $productos = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.idVenta', '=', 'ventas.idVenta')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select(
                'productos.nombre',
                'productos.codigo_interno',
                'categorias.nombre as categoria',
                DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_ventas.total_linea) as monto_total')
            )
            ->groupBy('productos.idProducto', 'productos.nombre', 'productos.codigo_interno', 'categorias.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reportes.productos-vendidos', compact(
            'productos', 'fechaInicio', 'fechaFin'
        ));
    }

    /**
     * Reporte: Stock crítico
     */
    public function stockCritico()
    {
        $productos = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->where('productos.activo', 1)
            ->whereColumn('productos.stock_actual', '<', 'productos.stock_minimo')
            ->select(
                'productos.*',
                'categorias.nombre as categoria',
                DB::raw('(productos.stock_minimo - productos.stock_actual) as faltante')
            )
            ->orderBy('faltante', 'desc')
            ->get();

        return view('admin.reportes.stock-critico', compact('productos'));
    }

    /**
     * Reporte: Movimientos de inventario
     */
    public function movimientos(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $tipo = $request->input('tipo');

        $query = DB::table('movimientos_inventario')
            ->join('productos', 'movimientos_inventario.idProducto', '=', 'productos.idProducto')
            ->join('users', 'movimientos_inventario.idUsuario', '=', 'users.id')
            ->whereBetween('movimientos_inventario.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);

        if ($tipo) {
            $query->where('movimientos_inventario.tipo', $tipo);
        }

        $movimientos = $query->select(
                'movimientos_inventario.*',
                'productos.nombre as producto',
                'users.name as usuario'
            )
            ->orderBy('movimientos_inventario.created_at', 'desc')
            ->get();

        return view('admin.reportes.movimientos', compact(
            'movimientos', 'fechaInicio', 'fechaFin', 'tipo'
        ));
    }

    // ==================================================
    // EXPORTACIÓN A PDF
    // ==================================================

    public function exportarVentasFechaPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $ventas = DB::table('ventas')
            ->join('users', 'ventas.idUsuario', '=', 'users.id')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select('ventas.*', 'users.name as vendedor')
            ->orderBy('ventas.created_at', 'desc')
            ->get();

        $data = [
            'ventas' => $ventas,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'totalVentas' => $ventas->count(),
            'montoTotal' => $ventas->sum('total'),
            'titulo' => 'Reporte de Ventas por Fecha',
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.ventas-fecha', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('reporte-ventas-' . $fechaInicio . '-al-' . $fechaFin . '.pdf');
    }

    public function exportarCategoriasPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $categorias = DB::table('ventas')
            ->join('detalle_ventas', 'ventas.idVenta', '=', 'detalle_ventas.idVenta')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select(
                'categorias.nombre',
                DB::raw('COUNT(DISTINCT ventas.idVenta) as total_ventas'),
                DB::raw('SUM(detalle_ventas.cantidad) as unidades_vendidas'),
                DB::raw('SUM(detalle_ventas.total_linea) as monto_total')
            )
            ->groupBy('categorias.idCategoria', 'categorias.nombre')
            ->orderBy('monto_total', 'desc')
            ->get();

        $data = [
            'categorias' => $categorias,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'titulo' => 'Ventas por Categoría',
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.ventas-categoria', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('reporte-categorias-' . $fechaInicio . '-al-' . $fechaFin . '.pdf');
    }

    public function exportarProductosPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());

        $productos = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.idVenta', '=', 'ventas.idVenta')
            ->join('productos', 'detalle_ventas.idProducto', '=', 'productos.idProducto')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->whereBetween('ventas.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('ventas.estado', 'Activa')
            ->select(
                'productos.nombre',
                'productos.codigo_interno',
                'categorias.nombre as categoria',
                DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_ventas.total_linea) as monto_total')
            )
            ->groupBy('productos.idProducto', 'productos.nombre', 'productos.codigo_interno', 'categorias.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'productos' => $productos,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'titulo' => 'Top 10 Productos Más Vendidos',
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.productos-vendidos', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('reporte-productos-' . $fechaInicio . '-al-' . $fechaFin . '.pdf');
    }

    public function exportarStockPDF()
    {
        $productos = DB::table('productos')
            ->join('categorias', 'productos.idCategoria', '=', 'categorias.idCategoria')
            ->where('productos.activo', 1)
            ->whereColumn('productos.stock_actual', '<', 'productos.stock_minimo')
            ->select(
                'productos.*',
                'categorias.nombre as categoria',
                DB::raw('(productos.stock_minimo - productos.stock_actual) as faltante')
            )
            ->orderBy('faltante', 'desc')
            ->get();

        $data = [
            'productos' => $productos,
            'titulo' => 'Reporte de Stock Crítico',
            'fecha' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.stock-critico', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('reporte-stock-critico-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportarMovimientosPDF(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $tipo = $request->input('tipo');

        $query = DB::table('movimientos_inventario')
            ->join('productos', 'movimientos_inventario.idProducto', '=', 'productos.idProducto')
            ->join('users', 'movimientos_inventario.idUsuario', '=', 'users.id')
            ->whereBetween('movimientos_inventario.created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);

        if ($tipo) {
            $query->where('movimientos_inventario.tipo', $tipo);
        }

        $movimientos = $query->select(
                'movimientos_inventario.*',
                'productos.nombre as producto',
                'users.name as usuario'
            )
            ->orderBy('movimientos_inventario.created_at', 'desc')
            ->get();

        $data = [
            'movimientos' => $movimientos,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'tipo' => $tipo,
            'titulo' => 'Movimientos de Inventario',
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf.movimientos', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('reporte-movimientos-' . $fechaInicio . '-al-' . $fechaFin . '.pdf');
    }
}