@extends('layouts.admin')

@section('title', 'Reportes')
@section('page-title', 'Reportes y Análisis')
@section('page-subtitle', 'Genera y exporta reportes del sistema')

@section('content')

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card" style="--color: #2563eb; --bg: #eff6ff;">
        <div class="stat-icon"><i class="fas fa-receipt"></i></div>
        <div class="stat-value">{{ $stats['total_ventas'] }}</div>
        <div class="stat-label">Ventas totales</div>
        <div class="stat-sub">Histórico completo</div>
    </div>

    <div class="stat-card" style="--color: #059669; --bg: #ecfdf5;">
        <div class="stat-icon"><i class="fas fa-coins"></i></div>
        <div class="stat-value">Q{{ number_format($stats['monto_total'], 2) }}</div>
        <div class="stat-label">Recaudado total</div>
        <div class="stat-sub">Q{{ number_format($stats['monto_hoy'], 2) }} hoy</div>
    </div>

    <div class="stat-card" style="--color: #7c3aed; --bg: #f5f3ff;">
        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
        <div class="stat-value">{{ $stats['total_productos'] }}</div>
        <div class="stat-label">Productos activos</div>
        <div class="stat-sub">En inventario</div>
    </div>

    <div class="stat-card" style="--color: #dc2626; --bg: #fef2f2;">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-value">{{ $stats['stock_critico'] }}</div>
        <div class="stat-label">Stock crítico</div>
        <div class="stat-sub">Requieren reposición</div>
    </div>
</div>

{{-- REPORTES DISPONIBLES --}}
<div class="section-head">
    <i class="fas fa-file-alt"></i>
    <h2>Reportes disponibles</h2>
</div>

<div class="reports-grid">
    <a href="{{ route('admin.reportes.ventas-fecha') }}" class="report-card" style="--color: #2563eb; --bg: #eff6ff;">
        <div class="report-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="report-info">
            <h3>Ventas por Fecha</h3>
            <p>Análisis con gráfica y totales por rango de fechas</p>
        </div>
        <i class="fas fa-arrow-right report-arrow"></i>
    </a>

    <a href="{{ route('admin.reportes.ventas-categoria') }}" class="report-card" style="--color: #7c3aed; --bg: #f5f3ff;">
        <div class="report-icon"><i class="fas fa-tags"></i></div>
        <div class="report-info">
            <h3>Ventas por Categoría</h3>
            <p>Distribución con gráfica circular por área</p>
        </div>
        <i class="fas fa-arrow-right report-arrow"></i>
    </a>

    <a href="{{ route('admin.reportes.productos-vendidos') }}" class="report-card" style="--color: #d97706; --bg: #fef3c7;">
        <div class="report-icon"><i class="fas fa-trophy"></i></div>
        <div class="report-info">
            <h3>Productos Más Vendidos</h3>
            <p>Top 10 con gráfica de barras</p>
        </div>
        <i class="fas fa-arrow-right report-arrow"></i>
    </a>

    <a href="{{ route('admin.reportes.stock-critico') }}" class="report-card" style="--color: #dc2626; --bg: #fef2f2;">
        <div class="report-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="report-info">
            <h3>Stock Crítico</h3>
            <p>Productos con stock bajo el mínimo</p>
        </div>
        <i class="fas fa-arrow-right report-arrow"></i>
    </a>

    <a href="{{ route('admin.reportes.movimientos') }}" class="report-card" style="--color: #059669; --bg: #ecfdf5;">
        <div class="report-icon"><i class="fas fa-exchange-alt"></i></div>
        <div class="report-info">
            <h3>Movimientos</h3>
            <p>Entradas y salidas por rango de fechas</p>
        </div>
        <i class="fas fa-arrow-right report-arrow"></i>
    </a>
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }
    .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(15,23,42,0.1); }
    .stat-icon { width: 48px; height: 48px; border-radius: 13px; background: var(--bg); color: var(--color); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 18px; }
    .stat-value { font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 700; color: #0f172a; line-height: 1; letter-spacing: -1.5px; margin-bottom: 8px; }
    .stat-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
    .stat-sub { font-size: 12px; color: #94a3b8; margin-top: 6px; }
    .section-head { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
    .section-head i { color: #2563eb; font-size: 16px; }
    .section-head h2 { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #0f172a; }
    .reports-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .report-card { display: flex; align-items: center; gap: 16px; padding: 22px; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; text-decoration: none; color: inherit; transition: all 0.35s ease; position: relative; overflow: hidden; }
    .report-card::before { content: ''; position: absolute; top: 0; left: 0; bottom: 0; width: 4px; background: var(--color); transform: scaleY(0); transition: transform 0.35s ease; }
    .report-card:hover::before { transform: scaleY(1); }
    .report-card:hover { transform: translateY(-6px); box-shadow: 0 25px 50px -15px rgba(15,23,42,0.12); border-color: transparent; }
    .report-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--bg); color: var(--color); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; transition: all 0.35s ease; }
    .report-card:hover .report-icon { background: var(--color); color: #fff; transform: scale(1.08) rotate(-6deg); }
    .report-info { flex: 1; }
    .report-info h3 { font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .report-info p { font-size: 12px; color: #64748b; line-height: 1.5; }
    .report-arrow { color: #cbd5e1; font-size: 14px; transition: all 0.3s ease; }
    .report-card:hover .report-arrow { color: var(--color); transform: translateX(4px); }
    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } .reports-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr; } .reports-grid { grid-template-columns: 1fr; } }
</style>

@endsection