@extends('layouts.admin')

@section('title', 'Movimientos')
@section('page-title', 'Movimientos de Inventario')
@section('page-subtitle', 'Historial de entradas, salidas y ajustes')

@section('content')

{{-- ALERTAS --}}
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

{{-- STATS --}}
<div class="stats-mini">
    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #2563eb, #0ea5e9);">
            <i class="fas fa-exchange-alt"></i>
        </div>
        <div>
            <div class="stat-mini-value">{{ $stats['total'] }}</div>
            <div class="stat-mini-label">Total movimientos</div>
        </div>
    </div>

    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
            <i class="fas fa-arrow-down"></i>
        </div>
        <div>
            <div class="stat-mini-value">{{ $stats['entradas'] }}</div>
            <div class="stat-mini-label">Entradas</div>
        </div>
    </div>

    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div>
            <div class="stat-mini-value">{{ $stats['salidas'] }}</div>
            <div class="stat-mini-label">Salidas</div>
        </div>
    </div>

    <div class="stat-mini">
        <div class="stat-mini-icon" style="background: linear-gradient(135deg, #7c3aed, #a855f7);">
            <i class="fas fa-sliders-h"></i>
        </div>
        <div>
            <div class="stat-mini-value">{{ $stats['ajustes'] }}</div>
            <div class="stat-mini-label">Ajustes</div>
        </div>
    </div>
</div>

{{-- CARD --}}
<div class="card">

    <div class="card-head">
        <form method="GET" class="filters">
            <div class="filter-input">
                <i class="fas fa-search filter-icon"></i>
                <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}">
            </div>

            <select name="tipo" class="filter-select">
                <option value="">Todos los tipos</option>
                <option value="Entrada" {{ request('tipo') == 'Entrada' ? 'selected' : '' }}>📥 Entrada</option>
                <option value="Salida" {{ request('tipo') == 'Salida' ? 'selected' : '' }}>📤 Salida</option>
                <option value="Ajuste" {{ request('tipo') == 'Ajuste' ? 'selected' : '' }}>🔧 Ajuste</option>
            </select>

            <input type="date" name="fecha" class="filter-date" value="{{ request('fecha') }}">

            <button class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>

            @if(request()->hasAny(['buscar', 'tipo', 'fecha']))
                <a href="{{ route('admin.movimientos.index') }}" class="btn btn-ghost">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            @endif
        </form>

        <a href="{{ route('admin.movimientos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Movimiento
        </a>
    </div>

    {{-- TABLA --}}
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="width: 130px;">Tipo</th>
                    <th style="width: 100px; text-align: center;">Cantidad</th>
                    <th style="width: 160px;">Stock</th>
                    <th style="width: 160px;">Usuario</th>
                    <th style="width: 160px;">Fecha</th>
                    <th style="width: 130px;">Estado</th>
                    <th style="width: 120px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                    <tr class="{{ $mov->anulado ? 'row-annulled' : '' }}">
                        <td>
                            <div class="product-cell">
                                <div class="product-icon {{ $mov->anulado ? 'icon-gray' : '' }}">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div>
                                    <div class="product-name">{{ $mov->producto_nombre }}</div>
                                    @if($mov->codigo_interno)
                                        <div class="product-code">{{ $mov->codigo_interno }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($mov->tipo == 'Entrada')
                                <span class="tipo-badge tipo-entrada">
                                    <i class="fas fa-arrow-down"></i> Entrada
                                </span>
                            @elseif($mov->tipo == 'Salida')
                                <span class="tipo-badge tipo-salida">
                                    <i class="fas fa-arrow-up"></i> Salida
                                </span>
                            @else
                                <span class="tipo-badge tipo-ajuste">
                                    <i class="fas fa-sliders-h"></i> Ajuste
                                </span>
                            @endif
                        </td>

                        <td style="text-align: center;">
                            <span class="qty-badge {{ $mov->cantidad > 0 ? 'qty-pos' : 'qty-neg' }}">
                                {{ $mov->cantidad > 0 ? '+' : '' }}{{ $mov->cantidad }}
                            </span>
                        </td>

                        <td>
                            <div class="stock-flow">
                                <span class="stock-from">{{ $mov->stock_anterior }}</span>
                                <i class="fas fa-arrow-right"></i>
                                <span class="stock-to">{{ $mov->stock_nuevo }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">{{ strtoupper(substr($mov->usuario_nombre, 0, 1)) }}</div>
                                <span>{{ $mov->usuario_nombre }}</span>
                            </div>
                        </td>

                        <td>
                            <div class="date-cell">
                                <div class="date-main">{{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y') }}</div>
                                <div class="date-sub">{{ \Carbon\Carbon::parse($mov->created_at)->format('H:i') }}</div>
                            </div>
                        </td>

                        <td>
                            @if($mov->anulado)
                                <span class="badge badge-red"><i class="fas fa-circle" style="font-size: 6px;"></i> Anulado</span>
                            @else
                                <span class="badge badge-green"><i class="fas fa-circle" style="font-size: 6px;"></i> Activo</span>
                            @endif
                        </td>

                        <td>
                            <div class="table-actions">
                                <a href="{{ route('admin.movimientos.show', $mov->idMovimiento) }}" class="btn-icon" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$mov->anulado)
                                    <form action="{{ route('admin.movimientos.destroy', $mov->idMovimiento) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Anular este movimiento? El stock será restaurado.')">
                                        @csrf @method('DELETE')
                                        <button class="btn-icon btn-icon-danger" title="Anular">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-exchange-alt"></i></div>
                                <h4>No hay movimientos registrados</h4>
                                <p>Los movimientos de inventario aparecerán aquí</p>
                                <a href="{{ route('admin.movimientos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Registrar movimiento
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($movimientos->hasPages())
        <div class="pagination">{{ $movimientos->appends(request()->query())->links() }}</div>
    @endif

</div>

<style>
    .alert { display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 500; }
    .alert-success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; }
    .alert-error { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
    .alert i { font-size: 16px; }

    .stats-mini { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    .stat-mini { display: flex; align-items: center; gap: 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; transition: all 0.3s ease; }
    .stat-mini:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -10px rgba(15,23,42,0.1); }
    .stat-mini-icon { width: 48px; height: 48px; border-radius: 13px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 19px; flex-shrink: 0; }
    .stat-mini-value { font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 4px; }
    .stat-mini-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
    .card-head { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 16px; justify-content: space-between; flex-wrap: wrap; }

    .filters { display: flex; gap: 10px; flex: 1; flex-wrap: wrap; }
    .filter-input { position: relative; flex: 1; min-width: 200px; }
    .filter-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 13px; pointer-events: none; }
    .filter-input input, .filter-select, .filter-date { width: 100%; padding: 11px 14px 11px 40px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-family: 'Inter', sans-serif; outline: none; transition: all 0.25s ease; color: #0f172a; background: #fff; }
    .filter-select, .filter-date { padding-left: 14px; min-width: 160px; cursor: pointer; }
    .filter-select { appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3e%3cpath fill='%2394a3b8' d='M6 8L0 0h12z'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
    .filter-input input:focus, .filter-select:focus, .filter-date:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }

    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; min-width: 1100px; }
    .table thead { background: #f8fafc; }
    .table th { padding: 14px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .table td { padding: 16px 20px; font-size: 13px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table tbody tr:hover { background: #f8fafc; }
    .table tbody tr:last-child td { border-bottom: none; }
    .row-annulled { opacity: 0.6; }
    .row-annulled .product-name { text-decoration: line-through; }

    .product-cell { display: flex; align-items: center; gap: 12px; }
    .product-icon { width: 40px; height: 40px; background: #eff6ff; color: #2563eb; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .icon-gray { background: #f1f5f9; color: #94a3b8; }
    .product-name { font-size: 13px; font-weight: 700; color: #0f172a; }
    .product-code { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: #94a3b8; margin-top: 2px; }

    .tipo-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .tipo-entrada { background: #ecfdf5; color: #059669; }
    .tipo-salida { background: #fef3c7; color: #d97706; }
    .tipo-ajuste { background: #f5f3ff; color: #7c3aed; }

    .qty-badge { display: inline-block; padding: 4px 12px; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 800; }
    .qty-pos { background: #ecfdf5; color: #059669; }
    .qty-neg { background: #fef2f2; color: #dc2626; }

    .stock-flow { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; }
    .stock-from { color: #94a3b8; }
    .stock-flow i { color: #cbd5e1; font-size: 10px; }
    .stock-to { color: #0f172a; font-weight: 800; font-family: 'Poppins', sans-serif; }

    .user-cell { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #334155; }
    .user-avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #7c3aed, #a855f7); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; flex-shrink: 0; }

    .date-cell { display: flex; flex-direction: column; gap: 2px; }
    .date-main { font-size: 13px; font-weight: 700; color: #0f172a; }
    .date-sub { font-size: 11px; color: #94a3b8; }

    .badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; font-size: 11px; font-weight: 700; border-radius: 100px; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-green { background: #ecfdf5; color: #059669; }
    .badge-red { background: #fef2f2; color: #dc2626; }
    .badge i { font-size: 6px; }

    .table-actions { display: flex; gap: 6px; }
    .btn-icon { width: 34px; height: 34px; background: #f1f5f9; border: none; border-radius: 9px; color: #475569; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.25s ease; text-decoration: none; font-size: 13px; }
    .btn-icon:hover { background: #2563eb; color: #fff; transform: translateY(-2px); }
    .btn-icon-danger:hover { background: #dc2626; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease; white-space: nowrap; }
    .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; box-shadow: 0 6px 16px rgba(37,99,235,0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(37,99,235,0.45); }
    .btn-ghost { background: #f1f5f9; color: #475569; }
    .btn-ghost:hover { background: #e2e8f0; }

    .empty-state { padding: 60px 24px; text-align: center; }
    .empty-icon { width: 80px; height: 80px; background: #f1f5f9; color: #94a3b8; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px; }
    .empty-state h4 { font-family: 'Poppins', sans-serif; font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
    .empty-state p { font-size: 13px; color: #64748b; margin-bottom: 24px; }

    .pagination { padding: 20px 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: center; }

    @media (max-width: 1200px) { .stats-mini { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) {
        .stats-mini { grid-template-columns: 1fr; }
        .card-head { padding: 16px; }
        .filters { flex-direction: column; width: 100%; }
        .filter-input, .filter-select, .filter-date { min-width: 100%; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

@endsection