@extends('layouts.admin')

@section('title', 'Detalle de Venta')
@section('page-title', 'Venta #' . str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT))
@section('page-subtitle', \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i') . ' hrs')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.ventas.index') }}" class="breadcrumb-link">
        <i class="fas fa-receipt"></i>
        Ventas
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">
        #{{ str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT) }}
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="detail-layout">

    {{-- ============================================
         COLUMNA IZQUIERDA - Detalle
    ============================================ --}}
    <div class="detail-main">

        {{-- Productos vendidos --}}
        <div class="card">
            <div class="card-head">
                <div class="card-head-title">
                    <i class="fas fa-boxes"></i>
                    Productos vendidos
                </div>
                <span class="card-head-count">
                    {{ $detalles->count() }} producto{{ $detalles->count() !== 1 ? 's' : '' }}
                </span>
            </div>

            @if($detalles->count() > 0)
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="width: 100px; text-align: center;">Cantidad</th>
                                <th style="width: 130px; text-align: right;">Precio unit.</th>
                                <th style="width: 130px; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalles as $detalle)
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            <div class="product-icon">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div>
                                                <div class="product-name">{{ $detalle->producto }}</div>
                                                @if($detalle->descuento > 0)
                                                    <div class="product-discount">
                                                        Descuento: Q{{ number_format($detalle->descuento, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="qty-badge">{{ $detalle->cantidad }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="price-unit">
                                            Q{{ number_format($detalle->precio_unitario, 2) }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="price-line">
                                            Q{{ number_format($detalle->total_linea, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right; padding-top: 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">
                                    Subtotal
                                </td>
                                <td style="text-align: right; padding-top: 20px;">
                                    <span class="price-summary">Q{{ number_format($venta->subtotal, 2) }}</span>
                                </td>
                            </tr>
                            @if($venta->descuento > 0)
                                <tr>
                                    <td colspan="3" style="text-align: right; font-size: 12px; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 1px;">
                                        Descuento
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="price-summary" style="color: #dc2626;">
                                            -Q{{ number_format($venta->descuento, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                            <tr class="row-total">
                                <td colspan="3" style="text-align: right; font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 1px;">
                                    Total
                                </td>
                                <td style="text-align: right;">
                                    <span class="price-total-final">
                                        Q{{ number_format($venta->total, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No hay productos registrados en esta venta</p>
                </div>
            @endif
        </div>

    </div>

    {{-- ============================================
         COLUMNA DERECHA - Info de venta
    ============================================ --}}
    <div class="detail-side">

        {{-- Estado de la venta --}}
        <div class="card">
            <div class="status-header {{ $venta->estado === 'Activa' ? 'status-active' : 'status-annulled' }}">
                <div class="status-icon">
                    <i class="fas {{ $venta->estado === 'Activa' ? 'fa-check-circle' : 'fa-ban' }}"></i>
                </div>
                <div>
                    <div class="status-title">
                        {{ $venta->estado === 'Activa' ? 'Venta activa' : 'Venta anulada' }}
                    </div>
                    <div class="status-desc">
                        @if($venta->estado === 'Activa')
                            Esta venta está registrada correctamente
                        @else
                            Esta venta fue anulada y el stock fue restaurado
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Info general --}}
        <div class="card">
            <div class="card-head">
                <div class="card-head-title">
                    <i class="fas fa-info-circle"></i>
                    Información de la venta
                </div>
            </div>

            <div class="info-list">

                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-hashtag"></i>
                        Número
                    </span>
                    <span class="info-value info-value-mono">
                        #{{ str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-calendar"></i>
                        Fecha y hora
                    </span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }}<br>
                        <span style="font-size: 12px; color: #64748b;">
                            {{ \Carbon\Carbon::parse($venta->created_at)->format('H:i') }} hrs
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-user"></i>
                        Vendedor
                    </span>
                    <span class="info-value">
                        <span class="seller-chip">
                            <span class="seller-chip-avatar">
                                {{ strtoupper(substr($venta->vendedor, 0, 1)) }}
                            </span>
                            {{ $venta->vendedor }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-envelope"></i>
                        Correo
                    </span>
                    <span class="info-value info-value-small">
                        {{ $venta->vendedor_email }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-boxes"></i>
                        Productos
                    </span>
                    <span class="info-value">
                        <strong>{{ $detalles->count() }}</strong> distinto{{ $detalles->count() !== 1 ? 's' : '' }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="fas fa-cubes"></i>
                        Total unidades
                    </span>
                    <span class="info-value">
                        <strong>{{ $detalles->sum('cantidad') }}</strong> unidades
                    </span>
                </div>

            </div>
        </div>

        {{-- Acciones --}}
        <div class="card actions-card">
            <div class="card-head">
                <div class="card-head-title">
                    <i class="fas fa-cog"></i>
                    Acciones
                </div>
            </div>

            <div class="actions-body">
                <a href="{{ route('admin.ventas.index') }}" class="btn btn-ghost btn-block">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al listado</span>
                </a>

                @if($venta->estado === 'Activa')
                    <form action="{{ route('admin.ventas.destroy', $venta->idVenta) }}"
                          method="POST"
                          onsubmit="return confirm('¿Anular la venta #{{ str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT) }}?\n\nEl stock de los productos será restaurado.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-ban"></i>
                            <span>Anular venta</span>
                        </button>
                    </form>
                @endif
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
    .breadcrumb-current {
        color: #64748b;
        font-weight: 600;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Alert */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    .alert-success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; }
    .alert i { font-size: 16px; }

    /* Layout */
    .detail-layout {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
        align-items: start;
    }

    .detail-main,
    .detail-side {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Card */
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
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-head-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .card-head-title i { color: #2563eb; font-size: 14px; }

    .card-head-count {
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 100px;
    }

    /* Tabla */
    .table-wrap { overflow-x: auto; }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 500px;
    }

    .table thead { background: #f8fafc; }

    .table th {
        padding: 12px 24px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        padding: 16px 24px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tbody tr:hover { background: #f8fafc; }
    .table tbody tr:last-child td { border-bottom: 1px solid #f1f5f9; }

    .table tfoot td {
        padding: 12px 24px;
        border-bottom: none;
        background: #f8fafc;
    }

    .row-total td {
        background: #f0f9ff !important;
        border-top: 2px solid #bfdbfe !important;
    }

    /* Producto celda */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-icon {
        width: 40px;
        height: 40px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .product-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .product-discount {
        font-size: 11px;
        color: #dc2626;
        font-weight: 600;
        margin-top: 2px;
    }

    .qty-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #f1f5f9;
        color: #334155;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
    }

    .price-unit {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
    }

    .price-line {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .price-summary {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .price-total-final {
        font-family: 'Poppins', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #059669;
        letter-spacing: -0.5px;
    }

    /* Status */
    .status-header {
        padding: 22px;
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .status-active {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border-bottom: 3px solid #10b981;
    }

    .status-annulled {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        border-bottom: 3px solid #ef4444;
    }

    .status-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .status-active .status-icon {
        background: #d1fae5;
        color: #059669;
    }

    .status-annulled .status-icon {
        background: #fecaca;
        color: #dc2626;
    }

    .status-title {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .status-active .status-title { color: #065f46; }
    .status-annulled .status-title { color: #991b1b; }

    .status-desc {
        font-size: 12px;
        line-height: 1.5;
    }

    .status-active .status-desc { color: #047857; }
    .status-annulled .status-desc { color: #b91c1c; }

    /* Info list */
    .info-list {
        padding: 8px 24px 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child { border-bottom: none; }

    .info-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        flex-shrink: 0;
    }

    .info-label i {
        color: #94a3b8;
        font-size: 12px;
        width: 14px;
    }

    .info-value {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        text-align: right;
        line-height: 1.5;
    }

    .info-value-mono {
        font-family: 'JetBrains Mono', monospace;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .info-value-small {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        word-break: break-all;
        max-width: 180px;
    }

    .seller-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px 4px 4px;
        background: #f5f3ff;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
        color: #6d28d9;
    }

    .seller-chip-avatar {
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
    }

    /* Actions */
    .actions-body {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 11px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        justify-content: center;
    }

    .btn-block { width: 100%; }

    .btn-ghost {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-ghost:hover { background: #e2e8f0; }

    .btn-danger {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(220, 38, 38, 0.45);
    }

    /* Empty */
    .empty-state {
        padding: 40px 24px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 32px;
        color: #cbd5e1;
        margin-bottom: 12px;
        display: block;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .detail-layout { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .table th, .table td { padding: 12px 16px; }
        .card-head { padding: 14px 16px; }
        .info-list { padding: 8px 16px 16px; }
        .actions-body { padding: 16px; }
    }
</style>

@endsection