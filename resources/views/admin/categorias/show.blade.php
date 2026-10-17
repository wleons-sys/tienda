@extends('layouts.admin')

@section('title', 'Ver Categoría')
@section('page-title', 'Detalle de Categoría')
@section('page-subtitle', $categoria->nombre)

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.categorias.index') }}" class="breadcrumb-link">
        <i class="fas fa-tags"></i>
        Categorías
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">{{ $categoria->nombre }}</span>
</div>

<div class="detail-layout">

    {{-- Info de la categoría --}}
    <div class="card detail-card">
        <div class="detail-header">
            <div class="detail-header-icon">
                <i class="fas fa-tag"></i>
            </div>
            <div class="detail-header-info">
                <h2 class="detail-header-title">{{ $categoria->nombre }}</h2>
                <div class="detail-header-meta">
                    <span class="detail-badge {{ $categoria->activo ? 'detail-badge-green' : 'detail-badge-red' }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i>
                        {{ $categoria->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                    <span class="detail-header-id">
                        ID: #{{ str_pad($categoria->idCategoria, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-body">

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-align-left"></i>
                    Descripción
                </span>
                <span class="detail-value">
                    {{ $categoria->descripcion ?? 'Sin descripción' }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-boxes"></i>
                    Productos
                </span>
                <span class="detail-value detail-value-highlight">
                    {{ $productos->total() }} producto{{ $productos->total() !== 1 ? 's' : '' }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-calendar-plus"></i>
                    Fecha de creación
                </span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($categoria->created_at)->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">
                    <i class="fas fa-clock"></i>
                    Última actualización
                </span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($categoria->updated_at)->diffForHumans() }}
                </span>
            </div>

        </div>

        <div class="detail-actions">
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left"></i>
                <span>Volver</span>
            </a>
            <a href="{{ route('admin.categorias.edit', $categoria->idCategoria) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                <span>Editar Categoría</span>
            </a>
        </div>
    </div>

    {{-- Productos de la categoría --}}
    <div class="card products-card">
        <div class="card-head">
            <div class="card-head-title-wrap">
                <h3 class="card-head-title">
                    <i class="fas fa-boxes"></i>
                    Productos en esta categoría
                </h3>
                <p class="card-head-desc">
                    Listado de productos activos registrados
                </p>
            </div>
            @if($productos->count() > 0)
                <span class="card-head-count">
                    {{ $productos->total() }} total
                </span>
            @endif
        </div>

        @if($productos->count() > 0)
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td>
                                    <span class="table-id">
                                        {{ $producto->codigo_interno ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $producto->nombre }}</strong>
                                </td>
                                <td>
                                    Q{{ number_format($producto->precio, 2) }}
                                </td>
                                <td>
                                    <span class="stock-badge
                                        {{ $producto->stock_actual < $producto->stock_minimo ? 'stock-critical' :
                                           ($producto->stock_actual <= $producto->stock_minimo * 2 ? 'stock-low' : 'stock-ok') }}">
                                        {{ $producto->stock_actual }} / {{ $producto->stock_minimo }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($productos->hasPages())
                <div class="pagination">
                    {{ $productos->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h4 class="empty-title">Sin productos</h4>
                <p class="empty-desc">
                    Esta categoría aún no tiene productos registrados
                </p>
                <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Agregar producto
                </a>
            </div>
        @endif
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
        transition: color 0.2s ease;
    }

    .breadcrumb-link:hover { color: #1e40af; }
    .breadcrumb-link i { font-size: 12px; }
    .breadcrumb-sep { color: #cbd5e1; font-size: 10px; }
    .breadcrumb-current { color: #64748b; font-weight: 500; }

    /* Layout */
    .detail-layout {
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

    /* Detail Card */
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
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 26px;
        flex-shrink: 0;
        box-shadow: 0 12px 28px -8px rgba(37, 99, 235, 0.4);
    }

    .detail-header-info { flex: 1; }

    .detail-header-title {
        font-family: 'Poppins', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        letter-spacing: -0.6px;
    }

    .detail-header-meta {
        display: flex;
        align-items: center;
        gap: 12px;
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

    .detail-badge-green {
        background: #ecfdf5;
        color: #059669;
    }

    .detail-badge-red {
        background: #fef2f2;
        color: #dc2626;
    }

    .detail-badge i {
        font-size: 6px;
    }

    .detail-header-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        padding: 5px 12px;
        border-radius: 8px;
    }

    /* Body */
    .detail-body {
        padding: 8px 28px 20px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 200px 1fr;
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

    .detail-label i {
        color: #94a3b8;
        font-size: 13px;
        width: 16px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        line-height: 1.6;
    }

    .detail-value-highlight {
        color: #2563eb;
        font-weight: 700;
        font-size: 15px;
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

    .btn-ghost:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Products Card */
    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-head-title {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 3px;
    }

    .card-head-title i {
        color: #2563eb;
        font-size: 14px;
    }

    .card-head-desc {
        font-size: 12px;
        color: #64748b;
    }

    .card-head-count {
        font-size: 11px;
        font-weight: 700;
        padding: 6px 14px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 100px;
    }

    /* Table */
    .table-wrap { overflow-x: auto; }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
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
    }

    .table tbody tr:hover { background: #f8fafc; }
    .table tbody tr:last-child td { border-bottom: none; }

    .table strong {
        font-weight: 700;
        color: #0f172a;
    }

    .table-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .stock-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
    }

    .stock-ok { background: #ecfdf5; color: #059669; }
    .stock-low { background: #fef3c7; color: #d97706; }
    .stock-critical { background: #fef2f2; color: #dc2626; }

    /* Empty */
    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: #f1f5f9;
        color: #94a3b8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 20px;
    }

    .empty-title {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .empty-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Pagination */
    .pagination {
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-header {
            padding: 20px;
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 6px;
        }

        .detail-header-title {
            font-size: 20px;
        }

        .detail-body { padding: 8px 20px 16px; }
        .detail-actions {
            padding: 16px 20px;
            flex-direction: column-reverse;
        }
        .detail-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection