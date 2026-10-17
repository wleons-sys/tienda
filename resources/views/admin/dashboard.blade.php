@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard | Multiservicios León')
@section('page-subtitle', 'Bienvenido, ' . auth()->user()->name)

@section('content')

{{-- ============================================
     STATS CARDS
============================================ --}}
<div class="stats-grid">

    {{-- Usuarios --}}
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
                <i class="fas fa-users"></i>
            </div>
            <span class="stat-trend">
                <i class="fas fa-arrow-up"></i> Activo
            </span>
        </div>
        <div class="stat-value">{{ $totalUsuarios }}</div>
        <div class="stat-label">Usuarios totales</div>
        <div class="stat-sub">
            <i class="fas fa-circle-check"></i>
            {{ $usuariosActivos }} activos en el sistema
        </div>
    </div>

    {{-- Stock Crítico --}}
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            @if($stockCritico > 0)
                <span class="stat-trend trend-danger">
                    <i class="fas fa-exclamation"></i> Alerta
                </span>
            @else
                <span class="stat-trend trend-ok">
                    <i class="fas fa-check"></i> OK
                </span>
            @endif
        </div>
        <div class="stat-value">{{ $stockCritico }}</div>
        <div class="stat-label">Stock crítico</div>
        <div class="stat-sub">
            <i class="fas fa-box"></i>
            Productos agotándose
        </div>
    </div>

    {{-- Ventas del Día --}}
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                <i class="fas fa-cash-register"></i>
            </div>
            <span class="stat-trend trend-warn">
                <i class="fas fa-clock"></i> Hoy
            </span>
        </div>
        <div class="stat-value">{{ $ventasHoy }}</div>
        <div class="stat-label">Ventas del día</div>
        <div class="stat-sub">
            <i class="fas fa-coins"></i>
            Q{{ number_format($montoVentasHoy, 2) }} recaudado
        </div>
    </div>

    {{-- Ventas del Mes --}}
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="stat-trend trend-ok">
                <i class="fas fa-arrow-up"></i> Mes
            </span>
        </div>
        <div class="stat-value">
            Q<span class="stat-unit">{{ number_format($ventasMes, 2) }}</span>
        </div>
        <div class="stat-label">Ventas del mes</div>
        <div class="stat-sub">
            <i class="fas fa-calendar"></i>
            Total acumulado
        </div>
    </div>

</div>

{{-- ============================================
     MÓDULOS DEL SISTEMA
============================================ --}}
<div class="section-head">
    <div class="section-title-wrap">
        <i class="fas fa-th-large"></i>
        <h2 class="section-title">Módulos del Sistema</h2>
    </div>
    <span class="section-date">{{ now()->format('d/m/Y') }}</span>
</div>

<div class="modules-grid">

    <a href="{{ route('admin.usuarios.index') }}" class="module-card" style="--color: #2563eb; --bg: #eff6ff;">
        <div class="module-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Usuarios</h3>
            <p class="module-desc">Gestionar usuarios y roles</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="{{ route('admin.categorias.index') }}" class="module-card" style="--color: #7c3aed; --bg: #f5f3ff;">
        <div class="module-icon">
            <i class="fas fa-tags"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Categorías</h3>
            <p class="module-desc">Administrar áreas de productos</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="{{ route('admin.productos.index') }}" class="module-card" style="--color: #0891b2; --bg: #ecfeff;">
        <div class="module-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Productos</h3>
            <p class="module-desc">Gestionar inventario</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="{{ route('admin.ventas.index') }}" class="module-card" style="--color: #059669; --bg: #ecfdf5;">
        <div class="module-icon">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Ventas</h3>
            <p class="module-desc">Historial de transacciones</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="#" class="module-card" style="--color: #d97706; --bg: #fef3c7;">
        <div class="module-icon">
            <i class="fas fa-exchange-alt"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Movimientos</h3>
            <p class="module-desc">Entradas y salidas</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="#" class="module-card" style="--color: #db2777; --bg: #fdf2f8;">
        <div class="module-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Reportes</h3>
            <p class="module-desc">Análisis y estadísticas</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="#" class="module-card" style="--color: #475569; --bg: #f1f5f9;">
        <div class="module-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Auditoría</h3>
            <p class="module-desc">Registro de actividad</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

    <a href="#" class="module-card" style="--color: #334155; --bg: #f1f5f9;">
        <div class="module-icon">
            <i class="fas fa-cog"></i>
        </div>
        <div class="module-info">
            <h3 class="module-name">Configuración</h3>
            <p class="module-desc">Ajustes del sistema</p>
        </div>
        <i class="fas fa-arrow-right module-arrow"></i>
    </a>

</div>

{{-- ============================================
     GRID: ACTIVIDADES + CATEGORÍAS
============================================ --}}
<div class="row-grid">

    {{-- Últimas Actividades --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title">
                <i class="fas fa-bolt"></i>
                Últimas Actividades
            </div>
            <a href="#" class="card-action">Ver todas</a>
        </div>

        <div class="activity-list">
            @forelse($ultimasActividades as $act)
                <div class="activity-item">
                    <div class="activity-dot
                        @if(str_contains($act->accion, 'Login')) green
                        @elseif(str_contains($act->accion, 'Logout')) amber
                        @elseif(str_contains($act->accion, 'Error') || str_contains($act->accion, 'Anulac')) red
                        @endif">
                    </div>
                    <div class="activity-body">
                        <div class="activity-title">{{ $act->accion }}</div>
                        <div class="activity-desc">
                            {{ $act->usuario_nombre ?? 'Sistema' }}
                            @if($act->descripcion)
                                · {{ $act->descripcion }}
                            @endif
                        </div>
                    </div>
                    <div class="activity-time">
                        {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No hay actividades registradas</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Categorías --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title">
                <i class="fas fa-tags"></i>
                Categorías
            </div>
            <span class="card-action">{{ $categorias->count() }} total</span>
        </div>

        <div class="table-head">
            <div>Categoría</div>
            <div>Productos</div>
            <div>Estado</div>
        </div>

        @forelse($categorias as $cat)
            <div class="table-row">
                <div class="table-name">{{ $cat->nombre }}</div>
                <div class="table-cell">
                    <span class="table-count">{{ $cat->total_productos }}</span>
                </div>
                <div>
                    <span class="badge badge-green">Activa</span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-tags"></i>
                <p>No hay categorías registradas</p>
            </div>
        @endforelse
    </div>

</div>

{{-- ============================================
     ESTILOS DEL DASHBOARD
============================================ --}}
<style>
    /* ============ STATS ============ */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 22px;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }

    .stat-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 19px;
        box-shadow: 0 8px 20px -6px rgba(15, 23, 42, 0.25);
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 100px;
        background: #ecfdf5;
        color: #059669;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-trend i { font-size: 9px; }

    .stat-trend.trend-danger {
        background: #fef2f2;
        color: #dc2626;
    }

    .stat-trend.trend-warn {
        background: #fef3c7;
        color: #d97706;
    }

    .stat-trend.trend-ok {
        background: #ecfdf5;
        color: #059669;
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

    .stat-value .stat-unit {
        font-size: 17px;
        color: #94a3b8;
        font-weight: 600;
        margin-left: 2px;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .stat-sub {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .stat-sub i {
        font-size: 11px;
        color: #cbd5e1;
    }

    /* ============ SECTION HEAD ============ */
    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .section-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title-wrap i {
        color: #2563eb;
        font-size: 15px;
    }

    .section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .section-date {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    /* ============ MÓDULOS ============ */
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }

    .module-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .module-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 3px;
        background: var(--color);
        transform: scaleY(0);
        transform-origin: center;
        transition: transform 0.35s ease;
    }

    .module-card:hover::before {
        transform: scaleY(1);
    }

    .module-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12);
        border-color: transparent;
    }

    .module-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: var(--bg);
        color: var(--color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        transition: all 0.35s ease;
    }

    .module-card:hover .module-icon {
        background: var(--color);
        color: #ffffff;
        transform: scale(1.08) rotate(-6deg);
    }

    .module-info {
        flex: 1;
        min-width: 0;
    }

    .module-name {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.2px;
    }

    .module-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
    }

    .module-arrow {
        color: #cbd5e1;
        font-size: 13px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .module-card:hover .module-arrow {
        color: var(--color);
        transform: translateX(4px);
    }

    /* ============ ROW GRID ============ */
    .row-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 20px;
    }

    /* ============ CARD ============ */
    .card {
        background: #ffffff;
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
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .card-title i {
        color: #2563eb;
        font-size: 14px;
    }

    .card-action {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .card-action:hover {
        color: #2563eb;
    }

    /* ============ ACTIVIDADES ============ */
    .activity-list {
        max-height: 420px;
        overflow-y: auto;
    }

    .activity-list::-webkit-scrollbar { width: 4px; }
    .activity-list::-webkit-scrollbar-track { background: transparent; }
    .activity-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

    .activity-item {
        display: flex;
        gap: 14px;
        padding: 14px 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .activity-item:last-child { border-bottom: none; }
    .activity-item:hover { background: #f8fafc; }

    .activity-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #0ea5e9;
        margin-top: 6px;
        flex-shrink: 0;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
    }

    .activity-dot.green { background: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12); }
    .activity-dot.red { background: #ef4444; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12); }
    .activity-dot.amber { background: #f59e0b; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12); }

    .activity-body { flex: 1; min-width: 0; }

    .activity-title {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 3px;
    }

    .activity-desc {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-time {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* ============ TABLA CATEGORÍAS ============ */
    .table-head {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        gap: 12px;
        padding: 12px 24px;
        background: #f8fafc;
        font-size: 10px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-row {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        gap: 12px;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
        transition: background 0.2s ease;
    }

    .table-row:hover { background: #f8fafc; }
    .table-row:last-child { border-bottom: none; }

    .table-name {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
    }

    .table-cell { font-size: 13px; }

    .table-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 26px;
        padding: 0 8px;
        background: #f1f5f9;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 10px;
        font-weight: 700;
        border-radius: 100px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-green { background: #ecfdf5; color: #059669; }

    /* ============ EMPTY STATE ============ */
    .empty-state {
        padding: 40px 24px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 34px;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .modules-grid { grid-template-columns: repeat(2, 1fr); }
        .row-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; gap: 14px; }
        .modules-grid { grid-template-columns: 1fr; gap: 12px; }
        .stat-value { font-size: 28px; }
        .activity-item, .table-row, .table-head { padding-left: 16px; padding-right: 16px; }
        .card-head { padding: 16px; }
        .activity-desc { white-space: normal; }
    }
</style>

@endsection