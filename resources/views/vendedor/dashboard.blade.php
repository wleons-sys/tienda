@extends('layouts.vendedor')

@section('title', 'Dashboard')
@section('page-title', 'Panel Vendedor')
@section('page-subtitle', 'Bienvenido, ' . auth()->user()->name)

@section('content')

{{-- ============================================
     STATS
============================================ --}}
<div class="stats">
    <div class="stat">
        <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="stat-value">{{ $ventasHoy }}</div>
        <div class="stat-label">Mis ventas hoy</div>
        <div class="stat-sub">Q{{ number_format($montoVentasHoy, 2) }} recaudado</div>
    </div>

    <div class="stat">
        <div class="stat-icon" style="background: #ecfeff; color: #0891b2;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-value">Q<span class="unit">{{ number_format($montoVentasMes, 2) }}</span></div>
        <div class="stat-label">Mis ventas del mes</div>
        <div class="stat-sub">{{ $ventasMes }} transacciones</div>
    </div>

    <div class="stat">
        <div class="stat-icon" style="background: #eff6ff; color: #1d4ed8;">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="stat-value">{{ $totalProductos }}</div>
        <div class="stat-label">Productos disponibles</div>
        <div class="stat-sub">En el inventario</div>
    </div>

    <div class="stat">
        <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-value">{{ $stockCritico }}</div>
        <div class="stat-label">Stock crítico</div>
        <div class="stat-sub">Productos agotándose</div>
    </div>
</div>

{{-- ============================================
     ACCIONES RÁPIDAS
============================================ --}}
<div class="quick-actions">

    <a href="{{ route('vendedor.pos.index') }}" class="qa"
       style="--grad: linear-gradient(135deg, #059669, #10b981); --shadow: rgba(16,185,129,0.3);">
        <div class="qa-icon">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="qa-name">Nueva Venta</div>
        <div class="qa-desc">Iniciar el punto de venta</div>
    </a>

    <a href="{{ route('vendedor.productos.index') }}" class="qa"
       style="--grad: linear-gradient(135deg, #2563eb, #0ea5e9); --shadow: rgba(37,99,235,0.3);">
        <div class="qa-icon">
            <i class="fas fa-search"></i>
        </div>
        <div class="qa-name">Consultar Productos</div>
        <div class="qa-desc">Ver stock y precios</div>
    </a>

    <a href="{{ route('vendedor.ventas.index') }}" class="qa"
       style="--grad: linear-gradient(135deg, #d97706, #f59e0b); --shadow: rgba(245,158,11,0.3);">
        <div class="qa-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="qa-name">Mis Ventas</div>
        <div class="qa-desc">Ver historial de ventas</div>
    </a>

</div>

{{-- ============================================
     GRID INFERIOR
============================================ --}}
<div class="row-grid">

    {{-- Ventas recientes --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title">
                <i class="fas fa-receipt"></i>
                Mis ventas recientes
            </div>
            <a href="{{ route('vendedor.ventas.index') }}" class="card-action">Ver todas</a>
        </div>

        @forelse($ultimasVentas as $venta)
            <div class="venta-item">
                <div class="venta-left">
                    <div class="venta-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="venta-info">
                        <div class="venta-num">Venta #{{ str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="venta-date">
                            {{ \Carbon\Carbon::parse($venta->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
                <div class="venta-total">
                    Q{{ number_format($venta->total, 2) }}
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                Aún no has realizado ventas
            </div>
        @endforelse
    </div>

    {{-- Top productos --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title">
                <i class="fas fa-fire"></i>
                Más vendidos por mí
            </div>
            <div class="card-action">Top 5</div>
        </div>

        @forelse($productosVendidos as $prod)
            <div class="prod-item">
                <div class="prod-name">{{ $prod->nombre }}</div>
                <div class="prod-count">{{ $prod->total_vendido }} uds.</div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-boxes"></i>
                Aún no hay productos vendidos
            </div>
        @endforelse
    </div>

</div>

@endsection

@section('styles')
<style>
    /* ============ STATS ============ */
    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
    }

    .stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(15,23,42,0.1);
    }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 18px;
    }

    .stat-value {
        font-family: 'Poppins', sans-serif;
        font-size: 34px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -1.5px;
        margin-bottom: 8px;
    }

    .stat-value .unit {
        font-size: 17px;
        color: #94a3b8;
        font-weight: 600;
        margin-left: 2px;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-sub {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 6px;
    }

    /* ============ ACCIONES RÁPIDAS ============ */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .qa {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px 24px;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .qa::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--grad);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .qa:hover::before { transform: scaleX(1); }

    .qa:hover {
        transform: translateY(-6px);
        box-shadow: 0 25px 50px -20px var(--shadow);
        border-color: transparent;
    }

    .qa-icon {
        width: 72px; height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: #fff;
        background: var(--grad);
        box-shadow: 0 12px 24px -8px var(--shadow);
        margin-bottom: 18px;
        transition: transform 0.4s ease;
    }

    .qa:hover .qa-icon {
        transform: scale(1.08) rotate(-6deg);
    }

    .qa-name {
        font-family: 'Poppins', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .qa-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
    }

    /* ============ GRID INFERIOR ============ */
    .row-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 20px;
    }

    .card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .card-title i {
        color: #10b981;
        font-size: 15px;
    }

    .card-action {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .card-action:hover { color: #10b981; }

    /* ============ VENTAS RECIENTES ============ */
    .venta-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .venta-item:last-child { border-bottom: none; }
    .venta-item:hover { background: #f8fafc; }

    .venta-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .venta-icon {
        width: 40px; height: 40px;
        background: #ecfdf5;
        color: #059669;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .venta-info { display: flex; flex-direction: column; }

    .venta-num {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .venta-date {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .venta-total {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #059669;
    }

    /* ============ TOP PRODUCTOS ============ */
    .prod-item {
        padding: 14px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .prod-item:last-child { border-bottom: none; }

    .prod-name {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
    }

    .prod-count {
        font-size: 12px;
        font-weight: 700;
        color: #0891b2;
        background: #ecfeff;
        padding: 3px 10px;
        border-radius: 100px;
    }

    .empty-state {
        padding: 40px 24px;
        text-align: center;
        color: #94a3b8;
        font-size: 13px;
    }

    .empty-state i {
        font-size: 36px;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 1200px) {
        .stats { grid-template-columns: repeat(2, 1fr); }
        .row-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .stats { grid-template-columns: 1fr; }
        .quick-actions { grid-template-columns: 1fr; }
    }
</style>
@endsection