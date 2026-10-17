@extends('layouts.admin')

@section('title', 'Categorías')
@section('page-title', 'Gestión de Categorías')
@section('page-subtitle', 'Administra las categorías de productos del sistema')

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
     CARD PRINCIPAL
============================================ --}}
<div class="card">

    {{-- HEAD --}}
    <div class="card-head">
        <div class="card-head-left">
            <h3 class="card-head-title">
                <i class="fas fa-tags"></i>
                Listado de Categorías
            </h3>
            <p class="card-head-desc">
                Total: <strong>{{ $categorias->total() }}</strong> categorías registradas
            </p>
        </div>

        <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            <span>Nueva Categoría</span>
        </a>
    </div>

    {{-- TABLA --}}
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="width: 120px;">Productos</th>
                    <th style="width: 120px;">Estado</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $categoria)
                    <tr>
                        {{-- ID --}}
                        <td>
                            <span class="table-id">
                                #{{ str_pad($categoria->idCategoria, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        {{-- Nombre --}}
                        <td>
                            <div class="table-cell-main">
                                <div class="table-cell-icon" style="background: #f0f9ff; color: #0284c7;">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <div class="table-cell-name">{{ $categoria->nombre }}</div>
                                    <div class="table-cell-sub">
                                        Creada {{ \Carbon\Carbon::parse($categoria->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Descripción --}}
                        <td>
                            <span class="table-desc">
                                {{ $categoria->descripcion ?? '—' }}
                            </span>
                        </td>

                        {{-- Productos --}}
                        <td>
                            <span class="table-count">
                                <i class="fas fa-boxes"></i>
                                {{ $categoria->total_productos }}
                            </span>
                        </td>

                        {{-- Estado --}}
                        <td>
                            @if($categoria->activo)
                                <span class="badge badge-green">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Activa
                                </span>
                            @else
                                <span class="badge badge-red">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Inactiva
                                </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.categorias.show', $categoria->idCategoria) }}"
                                   class="btn-icon" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.categorias.edit', $categoria->idCategoria) }}"
                                   class="btn-icon" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.categorias.destroy', $categoria->idCategoria) }}"
                                      method="POST"
                                      style="display:inline"
                                      onsubmit="return confirm('¿Eliminar la categoría {{ $categoria->nombre }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <h4 class="empty-title">No hay categorías registradas</h4>
                                <p class="empty-desc">
                                    Comienza creando tu primera categoría de productos
                                </p>
                                <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Crear primera categoría
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    @if($categorias->hasPages())
        <div class="pagination">
            {{ $categorias->links() }}
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
    .alert-success {
        background: #ecfdf5;
        border-left: 4px solid #10b981;
        color: #065f46;
    }
    .alert-error {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }
    .alert i { font-size: 16px; }

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
        gap: 20px;
        padding: 22px 24px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .card-head-title {
        font-family: 'Poppins', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .card-head-title i {
        color: #2563eb;
        font-size: 15px;
    }

    .card-head-desc {
        font-size: 13px;
        color: #64748b;
    }

    .card-head-desc strong {
        color: #0f172a;
        font-weight: 700;
    }

    /* Tabla */
    .table-wrap {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .table thead {
        background: #f8fafc;
    }

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

    .table tbody tr {
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ID */
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

    /* Celda nombre */
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

    /* Descripción */
    .table-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Conteo */
    .table-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
    }

    .table-count i {
        font-size: 11px;
    }

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

    .badge-green {
        background: #ecfdf5;
        color: #059669;
    }

    .badge-red {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-green i,
    .badge-red i {
        font-size: 6px;
    }

    /* Acciones */
    .table-actions {
        display: flex;
        gap: 6px;
    }

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

    .btn-icon-danger:hover {
        background: #dc2626;
    }

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

    /* Empty state */
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

    /* Paginación */
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
    }
</style>

@endsection