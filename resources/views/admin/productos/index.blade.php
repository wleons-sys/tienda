@extends('layouts.admin')

@section('title', 'Productos')
@section('page-title', 'Gestión de Productos')
@section('page-subtitle', 'Administra el inventario de productos')

@section('content')

{{-- ============================================
     ALERTAS
============================================ --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

{{-- ============================================
     FILTROS RÁPIDOS
============================================ --}}
<div class="quick-filters">
    <a href="{{ route('admin.productos.index') }}"
       class="qf {{ !request('estado') ? 'qf-active' : '' }}">
        <i class="fas fa-list"></i>
        <span>Todos</span>
    </a>
    <a href="{{ route('admin.productos.index', ['estado' => 'activos']) }}"
       class="qf {{ request('estado') === 'activos' ? 'qf-active' : '' }}">
        <i class="fas fa-check-circle"></i>
        <span>Activos</span>
    </a>
    <a href="{{ route('admin.productos.index', ['estado' => 'inactivos']) }}"
       class="qf {{ request('estado') === 'inactivos' ? 'qf-active' : '' }}">
        <i class="fas fa-times-circle"></i>
        <span>Inactivos</span>
    </a>
    <a href="{{ route('admin.productos.index', ['estado' => 'criticos']) }}"
       class="qf qf-danger {{ request('estado') === 'criticos' ? 'qf-active' : '' }}">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Stock crítico</span>
    </a>
</div>

{{-- ============================================
     CARD PRINCIPAL
============================================ --}}
<div class="card">

    {{-- HEAD con filtros --}}
    <div class="card-head">
        <form method="GET" class="filters">
            <div class="filter-input">
                <i class="fas fa-search filter-icon"></i>
                <input type="text"
                       name="buscar"
                       placeholder="Buscar por nombre o código..."
                       value="{{ request('buscar') }}">
            </div>

            <select name="categoria" class="filter-select">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->idCategoria }}"
                        {{ request('categoria') == $cat->idCategoria ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>

            <input type="hidden" name="estado" value="{{ request('estado') }}">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
                <span>Filtrar</span>
            </button>

            @if(request()->hasAny(['buscar', 'categoria', 'estado']))
                <a href="{{ route('admin.productos.index') }}" class="btn btn-ghost">
                    <i class="fas fa-times"></i>
                    <span>Limpiar</span>
                </a>
            @endif
        </form>

        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            <span>Nuevo Producto</span>
        </a>
    </div>

    {{-- INFO BAR --}}
    <div class="info-bar">
        <div class="info-item">
            <i class="fas fa-boxes"></i>
            <strong>{{ $productos->total() }}</strong> productos encontrados
        </div>
        @if(request('buscar') || request('categoria') || request('estado'))
            <div class="info-item info-item-filtered">
                <i class="fas fa-filter"></i>
                Filtros aplicados
            </div>
        @endif
    </div>

    {{-- TABLA --}}
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 130px;">Código</th>
                    <th>Producto</th>
                    <th style="width: 140px;">Categoría</th>
                    <th style="width: 110px;">Precio</th>
                    <th style="width: 150px;">Stock</th>
                    <th style="width: 110px;">Estado</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    @php
                        $stockStatus = 'ok';
                        if ($producto->stock_actual < $producto->stock_minimo) {
                            $stockStatus = 'critical';
                        } elseif ($producto->stock_actual <= $producto->stock_minimo * 2) {
                            $stockStatus = 'low';
                        }
                    @endphp
                    <tr>
                        {{-- Código --}}
                        <td>
                            @if($producto->codigo_interno)
                                <span class="table-id">
                                    {{ $producto->codigo_interno }}
                                </span>
                            @else
                                <span class="table-id-empty">Sin código</span>
                            @endif
                        </td>

                        {{-- Producto --}}
                        <td>
                            <div class="table-cell-main">
                                <div class="table-cell-icon
                                    {{ $stockStatus === 'critical' ? 'icon-red' : ($stockStatus === 'low' ? 'icon-amber' : 'icon-blue') }}">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <div class="table-cell-name">{{ $producto->nombre }}</div>
                                    <div class="table-cell-sub">
                                        Actualizado {{ \Carbon\Carbon::parse($producto->updated_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Categoría --}}
                        <td>
                            <span class="cat-badge">
                                <i class="fas fa-tag" style="font-size: 9px;"></i>
                                {{ $producto->categoria_nombre }}
                            </span>
                        </td>

                        {{-- Precio --}}
                        <td>
                            <span class="price-value">
                                Q{{ number_format($producto->precio, 2) }}
                            </span>
                        </td>

                        {{-- Stock --}}
                        <td>
                            <div class="stock-wrapper">
                                <div class="stock-info">
                                    <span class="stock-value">{{ $producto->stock_actual }}</span>
                                    <span class="stock-min">/ {{ $producto->stock_minimo }} mín.</span>
                                </div>
                                <div class="stock-bar">
                                    <div class="stock-bar-fill
                                        {{ $stockStatus === 'critical' ? 'fill-red' : ($stockStatus === 'low' ? 'fill-amber' : 'fill-green') }}"
                                        style="width: {{ min(100, ($producto->stock_actual / max(1, $producto->stock_minimo * 3)) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Estado --}}
                        <td>
                            @if($producto->activo)
                                <span class="badge badge-green">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Activo
                                </span>
                            @else
                                <span class="badge badge-red">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.productos.show', $producto->idProducto) }}"
                                   class="btn-icon" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.productos.edit', $producto->idProducto) }}"
                                   class="btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.productos.destroy', $producto->idProducto) }}"
                                      method="POST"
                                      style="display:inline"
                                      onsubmit="return confirm('¿Dar de baja el producto {{ $producto->nombre }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="Dar de baja">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h4 class="empty-title">No hay productos registrados</h4>
                                <p class="empty-desc">
                                    Comienza agregando tu primer producto al inventario
                                </p>
                                <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Crear primer producto
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    @if($productos->hasPages())
        <div class="pagination">
            {{ $productos->appends(request()->query())->links() }}
        </div>
    @endif

</div>

{{-- ============================================
     ESTILOS
============================================ --}}
<style>
    /* Alerts */
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
    .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
    .alert i { font-size: 16px; }

    /* Quick filters */
    .quick-filters {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .qf {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 100px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .qf i { font-size: 12px; color: #94a3b8; transition: color 0.25s ease; }

    .qf:hover {
        border-color: #2563eb;
        color: #2563eb;
    }

    .qf:hover i { color: #2563eb; }

    .qf-active {
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        border-color: transparent;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .qf-active i { color: #ffffff !important; }

    .qf-danger.qf-active {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
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
        gap: 16px;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    /* Filtros */
    .filters {
        display: flex;
        gap: 10px;
        flex: 1;
        flex-wrap: wrap;
    }

    .filter-input {
        position: relative;
        flex: 1;
        min-width: 240px;
    }

    .filter-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .filter-input input {
        width: 100%;
        padding: 11px 14px 11px 40px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.25s ease;
        color: #0f172a;
    }

    .filter-input input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .filter-select {
        padding: 11px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.25s ease;
        background: #ffffff;
        color: #0f172a;
        cursor: pointer;
        min-width: 180px;
    }

    .filter-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* Info bar */
    .info-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 12px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .info-item i {
        color: #94a3b8;
        font-size: 12px;
    }

    .info-item strong {
        color: #0f172a;
        font-weight: 700;
    }

    .info-item-filtered {
        padding: 5px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 100px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item-filtered i { color: #3b82f6; }

    /* Tabla */
    .table-wrap { overflow-x: auto; }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .table thead { background: #f8fafc; }

    .table th {
        padding: 14px 24px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table td {
        padding: 16px 24px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tbody tr { transition: background 0.2s ease; }
    .table tbody tr:hover { background: #f8fafc; }
    .table tbody tr:last-child td { border-bottom: none; }

    /* Código */
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

    .table-id-empty {
        font-size: 11px;
        color: #cbd5e1;
        font-style: italic;
    }

    /* Nombre celda */
    .table-cell-main {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-cell-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-amber { background: #fef3c7; color: #d97706; }
    .icon-red { background: #fef2f2; color: #dc2626; }

    .table-cell-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .table-cell-sub {
        font-size: 11px;
        color: #94a3b8;
    }

    /* Categoría badge */
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
        letter-spacing: 0.3px;
    }

    /* Precio */
    .price-value {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    /* Stock */
    .stock-wrapper {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 110px;
    }

    .stock-info {
        display: flex;
        align-items: baseline;
        gap: 4px;
    }

    .stock-value {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .stock-min {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 500;
    }

    .stock-bar {
        width: 100%;
        height: 4px;
        background: #f1f5f9;
        border-radius: 100px;
        overflow: hidden;
    }

    .stock-bar-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 0.5s ease;
    }

    .fill-green { background: linear-gradient(90deg, #10b981, #34d399); }
    .fill-amber { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .fill-red { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* Badges */
    .badge {
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

    .badge-green { background: #ecfdf5; color: #059669; }
    .badge-red { background: #fef2f2; color: #dc2626; }
    .badge-green i, .badge-red i { font-size: 6px; }

    /* Acciones */
    .table-actions { display: flex; gap: 6px; }

    .btn-icon {
        width: 34px;
        height: 34px;
        background: #f1f5f9;
        border: none;
        border-radius: 9px;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        font-size: 13px;
    }

    .btn-icon:hover {
        background: #2563eb;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .btn-icon-danger:hover { background: #dc2626; }

    /* Botones */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
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
        background: #f1f5f9;
        color: #475569;
    }

    .btn-ghost:hover { background: #e2e8f0; }

    /* Empty state */
    .empty-state { padding: 60px 24px; text-align: center; }

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
        font-size: 17px;
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
    @media (max-width: 640px) {
        .card-head { padding: 16px; }
        .table th, .table td { padding: 12px 16px; }
        .info-bar { padding: 10px 16px; }
        .filters { flex-direction: column; width: 100%; }
        .filter-input { min-width: 100%; }
        .btn { width: 100%; justify-content: center; }
        .card-head > a.btn { width: 100%; }
    }
</style>

@endsection