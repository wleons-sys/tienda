<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;

// ============ CONTROLADORES ADMIN ============
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\MovimientoController;
use App\Http\Controllers\Admin\ReporteController as AdminReporte;

// ============ CONTROLADORES VENDEDOR ============
use App\Http\Controllers\Vendedor\DashboardController as VendedorDashboard;
use App\Http\Controllers\Vendedor\PosController;
use App\Http\Controllers\Vendedor\ProductoController as VendedorProducto;
use App\Http\Controllers\Vendedor\VentaController as VendedorVenta;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', [PortalController::class, 'index'])->name('portal');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ============ DASHBOARD ============
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // ============ GESTIÓN DE USUARIOS ============
    Route::resource('usuarios', UsuarioController::class);

    // ============ GESTIÓN DE CATEGORÍAS ============
    Route::resource('categorias', CategoriaController::class);

    // ============ GESTIÓN DE PRODUCTOS ============
    Route::resource('productos', ProductoController::class);

    // ============ GESTIÓN DE VENTAS ============
    Route::resource('ventas', VentaController::class);

    // ============ GESTIÓN DE MOVIMIENTOS ============
    Route::resource('movimientos', MovimientoController::class);

    // ============ REPORTES ============
    Route::prefix('reportes')->name('reportes.')->group(function () {

        // Panel principal de reportes
        Route::get('/', [AdminReporte::class, 'index'])->name('index');

        // Reporte: Ventas por Fecha
        Route::get('/ventas-fecha', [AdminReporte::class, 'ventasPorFecha'])->name('ventas-fecha');
        Route::get('/ventas-fecha/pdf', [AdminReporte::class, 'exportarVentasFechaPDF'])->name('ventas-fecha.pdf');

        // Reporte: Ventas por Categoría
        Route::get('/ventas-categoria', [AdminReporte::class, 'ventasPorCategoria'])->name('ventas-categoria');
        Route::get('/ventas-categoria/pdf', [AdminReporte::class, 'exportarCategoriasPDF'])->name('ventas-categoria.pdf');

        // Reporte: Productos Más Vendidos
        Route::get('/productos-vendidos', [AdminReporte::class, 'productosMasVendidos'])->name('productos-vendidos');
        Route::get('/productos-vendidos/pdf', [AdminReporte::class, 'exportarProductosPDF'])->name('productos-vendidos.pdf');

        // Reporte: Stock Crítico
        Route::get('/stock-critico', [AdminReporte::class, 'stockCritico'])->name('stock-critico');
        Route::get('/stock-critico/pdf', [AdminReporte::class, 'exportarStockPDF'])->name('stock-critico.pdf');

        // Reporte: Movimientos de Inventario
        Route::get('/movimientos', [AdminReporte::class, 'movimientos'])->name('movimientos');
        Route::get('/movimientos/pdf', [AdminReporte::class, 'exportarMovimientosPDF'])->name('movimientos.pdf');
    });
});

/*
|--------------------------------------------------------------------------
| RUTAS VENDEDOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('vendedor')->name('vendedor.')->group(function () {

    // ============ DASHBOARD ============
    Route::get('/dashboard', [VendedorDashboard::class, 'index'])->name('dashboard');

    // ============ PUNTO DE VENTA (POS) ============
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::get('/buscar', [PosController::class, 'buscar'])->name('buscar');
        Route::post('/procesar', [PosController::class, 'procesar'])->name('procesar');
    });

    // ============ MIS VENTAS ============
    Route::get('/ventas', [VendedorVenta::class, 'index'])->name('ventas.index');
    Route::get('/ventas/{id}', [VendedorVenta::class, 'show'])->name('ventas.show');

    // ============ CONSULTAR PRODUCTOS ============
    Route::get('/productos', [VendedorProducto::class, 'index'])->name('productos.index');
});