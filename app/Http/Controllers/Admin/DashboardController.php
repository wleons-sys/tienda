<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============================================
        // ESTADÍSTICAS PRINCIPALES
        // ============================================

        // 1. Total de usuarios
        $totalUsuarios = User::count();
        $usuariosActivos = User::where('activo', 1)->count();

        // 2. Stock crítico (productos con stock_actual < stock_minimo)
        $stockCritico = DB::table('productos')
            ->where('activo', 1)
            ->whereColumn('stock_actual', '<', 'stock_minimo')
            ->count();

        // 3. Total de productos
        $totalProductos = DB::table('productos')
            ->where('activo', 1)
            ->count();

        // 4. Ventas del día
        $ventasHoy = DB::table('ventas')
            ->whereDate('created_at', today())
            ->where('estado', 'Activa')
            ->count();

        $montoVentasHoy = DB::table('ventas')
            ->whereDate('created_at', today())
            ->where('estado', 'Activa')
            ->sum('total');

        // 5. Ventas del mes
        $ventasMes = DB::table('ventas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('estado', 'Activa')
            ->sum('total');

        // ============================================
        // ÚLTIMAS ACTIVIDADES (Auditoría)
        // ============================================
        $ultimasActividades = DB::table('logs_auditoria')
            ->leftJoin('users', 'logs_auditoria.idUsuario', '=', 'users.id')
            ->select(
                'logs_auditoria.idLog',
                'logs_auditoria.accion',
                'logs_auditoria.descripcion',
                'logs_auditoria.created_at',
                'users.name as usuario_nombre'
            )
            ->orderBy('logs_auditoria.created_at', 'desc')
            ->limit(8)
            ->get();

        // ============================================
        // CATEGORÍAS CON CONTEO DE PRODUCTOS
        // ============================================
        $categorias = DB::table('categorias')
            ->leftJoin('productos', function ($join) {
                $join->on('categorias.idCategoria', '=', 'productos.idCategoria')
                    ->where('productos.activo', '=', 1);
            })
            ->select(
                'categorias.idCategoria',
                'categorias.nombre',
                'categorias.descripcion',
                DB::raw('COUNT(productos.idProducto) as total_productos')
            )
            ->groupBy('categorias.idCategoria', 'categorias.nombre', 'categorias.descripcion')
            ->get();

        // ============================================
        // DATOS DEL USUARIO
        // ============================================
        $usuario = auth()->user();

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'usuariosActivos',
            'stockCritico',
            'totalProductos',
            'ventasHoy',
            'montoVentasHoy',
            'ventasMes',
            'ultimasActividades',
            'categorias',
            'usuario'
        ));
    }
}