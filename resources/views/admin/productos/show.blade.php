@extends('layouts.admin')

@section('title', 'Ver Producto')
@section('page-title', 'Detalle del Producto')
@section('page-subtitle', $producto->nombre)

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.productos.index') }}" class="breadcrumb-link">
        <i class="fas fa-boxes"></i>
        Productos
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">{{ $producto->nombre }}</span>
</div>

@php
    $stockStatus = 'ok';
    $stockLabel = 'Stock normal';
    $stockColor = 'green';
    if ($producto->stock_actual < $producto->stock_minimo) {
        $stockStatus = 'critical';
        $stockLabel = 'Stock crítico';
        $stockColor = 'red';
    } elseif ($producto->stock_actual <= $producto->stock_minimo * 2) {
        $stockStatus = 'low';
        $stockLabel = 'Stock bajo';
        $stockColor = 'amber';
    }
@endphp

<div class="detail-layout">

    {{-- Card principal --}}
    <div class="card detail-card">
        <div class="detail-header">
            <div class="detail-header-icon
                {{ $stockStatus === 'critical' ? 'icon-red' : ($stockStatus === 'low' ? 'icon-amber' : 'icon-blue') }}">
                <i class="fas fa-box"></i>
            </div>
            <div class="detail-header-info">
                <h2 class="detail-header-title">{{ $producto->nombre }}</h2>
                <div class="detail-header-meta">
                    <span class="detail-badge detail-badge-{{ $producto->activo ? 'green' : 'red' }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i>
                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="detail-badge detail-badge-{{ $stockColor }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i>
                        {{ $stockLabel }}
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-body">

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-barcode"></i> Código interno</span>
                <span class="detail-value">
                    @if($producto->codigo_interno)
                        <span class="table-id">{{ $producto->codigo_interno }}</span>
                    @else
                        <span style="color: #94a3b8; font-style: italic;">Sin código asignado</span>
                    @endif
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-tags"></i> Categoría</span>
                <span class="detail-value">
                    <span class="cat-badge">
                        <i class="fas fa-tag" style="font-size: 9px;"></i>
                        {{ $producto->categoria_nombre }}
                    </span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-dollar-sign"></i> Precio de venta</span>
                <span class="detail-value detail-price">
                    Q{{ number_format($producto->precio, 2) }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-cubes"></i> Stock actual</span>
                <span class="detail-value">
                    <strong style="font-size: 16px; color: #0f172a;">{{ $producto->stock_actual }}</strong>
                    <span style="color: #94a3b8; font-size: 13px;">unidades</span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-exclamation-triangle"></i> Stock mínimo</span>
                <span class="detail-value">
                    <strong>{{ $producto->stock_minimo }}</strong> unidades
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar-plus"></i> Creado</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($producto->created_at)->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-clock"></i> Actualizado</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($producto->updated_at)->diffForHumans() }}
                </span>
            </div>

        </div>

        <div class="detail-actions">
            <a href="{{ route('admin.productos.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left"></i>
                <span>Volver</span>
            </a>
            <a href="{{ route('admin.productos.edit', $producto->idProducto) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                <span>Editar Producto</span>
            </a>
        </div>
    </div>

    {{-- Card de estado del stock --}}
    <div class="card stock-card">
        <div class="card-head">
            <div class="card-head-title">
                <i class="fas fa-chart-bar"></i>
                Estado del stock
            </div>
        </div>

        <div class="stock-card-body">

            <div class="stock-alert
                {{ $stockStatus === 'critical' ? 'stock-alert-red' : ($stockStatus === 'low' ? 'stock-alert-amber' : 'stock-alert-green') }}">
                <div class="stock-alert-icon">
                    <i class="fas
                        {{ $stockStatus === 'critical' ? 'fa-exclamation-triangle' : ($stockStatus === 'low' ? 'fa-info-circle' : 'fa-check-circle') }}">
                    </i>
                </div>
                <div>
                    <div class="stock-alert-title">{{ $stockLabel }}</div>
                    <div class="stock-alert-desc">
                        @if($stockStatus === 'critical')
                            El stock actual está por debajo del mínimo. Se recomienda reponer urgentemente.
                        @elseif($stockStatus === 'low')
                            El stock está cerca del mínimo. Considera reponer pronto.
                        @else
                            El stock está en un nivel saludable.
                        @endif
                    </div>
                </div>
            </div>

            <div class="stock-progress">
                <div class="stock-progress-head">
                    <span>Nivel de stock</span>
                    <strong>{{ $producto->stock_actual }} / {{ $producto->stock_minimo * 3 }}</strong>
                </div>
                <div class="stock-progress-bar">
                    <div class="stock-progress-fill
                        {{ $stockStatus === 'critical' ? 'fill-red' : ($stockStatus === 'low' ? 'fill-amber' : 'fill-green') }}"
                        style="width: {{ min(100, ($producto->stock_actual / max(1, $producto->stock_minimo * 3)) * 100) }}%">
                    </div>
                </div>
            </div>

            <div class="stock-stats">
                <div class="stock-stat">
                    <span class="stock-stat-label">Actual</span>
                    <span class="stock-stat-value">{{ $producto->stock_actual }}</span>
                </div>
                <div class="stock-stat">
                    <span class="stock-stat-label">Mínimo</span>
                    <span class="stock-stat-value">{{ $producto->stock_minimo }}</span>
                </div>
                <div class="stock-stat">
                    <span class="stock-stat-label">Diferencia</span>
                    <span class="stock-stat-value">
                        {{ $producto->stock_actual - $producto->stock_minimo }}
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-size: 13px;
    }
    .breadcrumb-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }
    .breadcrumb-link:hover { color: #1e40af; }
    .breadcrumb-link i { font-size: 12px; }
    .breadcrumb-sep { color: #cbd5e1; font-size: 10px; }
    .breadcrumb-current { color: #64748b; font-weight: 500; }

    /* Layout */
    .detail-layout {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
        align-items: start;
    }

    /* Card */
    .card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    /* Detail Header */
    .detail-header {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 28px;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
    }

    .detail-header-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 26px;
        flex-shrink: 0;
    }

    .icon-blue {
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        box-shadow: 0 12px 28px -8px rgba(37, 99, 235, 0.4);
    }

    .icon-amber {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        box-shadow: 0 12px 28px -8px rgba(217, 119, 6, 0.4);
    }

    .icon-red {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        box-shadow: 0 12px 28px -8px rgba(220, 38, 38, 0.4);
    }

    .detail-header-info { flex: 1; }

    .detail-header-title {
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .detail-header-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .detail-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 100px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-badge i { font-size: 6px; }
    .detail-badge-green { background: #ecfdf5; color: #059669; }
    .detail-badge-red { background: #fef2f2; color: #dc2626; }
    .detail-badge-amber { background: #fef3c7; color: #d97706; }

    /* Body */
    .detail-body { padding: 8px 28px 20px; }

    .detail-row {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 20px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
        align-items: center;
    }

    .detail-row:last-child { border-bottom: none; }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .detail-label i { color: #94a3b8; font-size: 13px; width: 16px; }

    .detail-value {
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        line-height: 1.6;
    }

    .detail-price {
        font-family: 'Poppins', sans-serif;
        font-size: 20px !important;
        font-weight: 800;
        color: #059669 !important;
    }

    .table-id {
        display: inline-block;
        padding: 4px 10px;
        background: #f1f5f9;
        border-radius: 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
    }

    .cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: #f5f3ff;
        color: #7c3aed;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Actions */
    .detail-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 28px;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: 11px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45);
    }

    .btn-ghost {
        background: #ffffff;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }

    .btn-ghost:hover { background: #f8fafc; }

    /* Stock card */
    .card-head {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-head-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .card-head-title i { color: #2563eb; font-size: 14px; }

    .stock-card-body { padding: 20px 24px; }

    /* Stock alert */
    .stock-alert {
        display: flex;
        gap: 14px;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 22px;
    }

    .stock-alert-green { background: #ecfdf5; }
    .stock-alert-amber { background: #fef3c7; }
    .stock-alert-red { background: #fef2f2; }

    .stock-alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .stock-alert-green .stock-alert-icon { background: #d1fae5; color: #059669; }
    .stock-alert-amber .stock-alert-icon { background: #fde68a; color: #d97706; }
    .stock-alert-red .stock-alert-icon { background: #fecaca; color: #dc2626; }

    .stock-alert-title {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .stock-alert-green .stock-alert-title { color: #065f46; }
    .stock-alert-amber .stock-alert-title { color: #78350f; }
    .stock-alert-red .stock-alert-title { color: #991b1b; }

    .stock-alert-desc {
        font-size: 12px;
        line-height: 1.5;
    }

    .stock-alert-green .stock-alert-desc { color: #047857; }
    .stock-alert-amber .stock-alert-desc { color: #92400e; }
    .stock-alert-red .stock-alert-desc { color: #b91c1c; }

    /* Progress */
    .stock-progress { margin-bottom: 20px; }

    .stock-progress-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .stock-progress-head strong { color: #0f172a; font-weight: 700; font-size: 13px; }

    .stock-progress-bar {
        height: 8px;
        background: #f1f5f9;
        border-radius: 100px;
        overflow: hidden;
    }

    .stock-progress-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 0.5s ease;
    }

    .fill-green { background: linear-gradient(90deg, #10b981, #34d399); }
    .fill-amber { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .fill-red { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Stats */
    .stock-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .stock-stat {
        text-align: center;
        padding: 14px 10px;
        background: #f8fafc;
        border-radius: 11px;
    }

    .stock-stat-label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .stock-stat-value {
        display: block;
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .detail-layout { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .detail-header {
            padding: 20px;
            flex-direction: column;
            align-items: flex-start;
        }
        .detail-header-title { font-size: 18px; }
        .detail-body { padding: 8px 20px 16px; }
        .detail-row {
            grid-template-columns: 1fr;
            gap: 6px;
        }
        .detail-actions {
            padding: 16px 20px;
            flex-direction: column-reverse;
        }
        .detail-actions .btn { width: 100%; justify-content: center; }
    }
</style>

@endsection