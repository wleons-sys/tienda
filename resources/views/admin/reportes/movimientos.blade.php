@extends('layouts.admin')

@section('title', 'Movimientos de Inventario')
@section('page-title', 'Reporte de Movimientos de Inventario')
@section('page-subtitle', 'Entradas y salidas por rango de fechas')

@section('content')

<div class="filter-card">
    <form method="GET" class="filters">
        <div class="fg">
            <label>Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" required>
        </div>
        <div class="fg">
            <label>Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" required>
        </div>
        <div class="fg">
            <label>Tipo</label>
            <select name="tipo">
                <option value="">Todos</option>
                <option value="Entrada" {{ $tipo == 'Entrada' ? 'selected' : '' }}>📥 Entrada</option>
                <option value="Salida" {{ $tipo == 'Salida' ? 'selected' : '' }}>📤 Salida</option>
                <option value="Ajuste" {{ $tipo == 'Ajuste' ? 'selected' : '' }}>🔧 Ajuste</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Generar</button>
        <a href="{{ route('admin.reportes.movimientos.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin, 'tipo' => $tipo]) }}"
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </form>
</div>

<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-exchange-alt"></i> Movimientos de inventario</div>
        <span class="badge-info">{{ $movimientos->count() }} movimientos</span>
    </div>

    @if($movimientos->count() > 0)
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Stock</th>
                        <th>Usuario</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $mov)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $mov->producto }}</strong></td>
                            <td>
                                @if($mov->tipo == 'Entrada')
                                    <span class="tipo-badge entrada">📥 Entrada</span>
                                @elseif($mov->tipo == 'Salida')
                                    <span class="tipo-badge salida">📤 Salida</span>
                                @else
                                    <span class="tipo-badge ajuste">🔧 Ajuste</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <span class="qty-badge {{ $mov->cantidad > 0 ? 'pos' : 'neg' }}">
                                    {{ $mov->cantidad > 0 ? '+' : '' }}{{ $mov->cantidad }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="stock-flow">{{ $mov->stock_anterior }} → {{ $mov->stock_nuevo }}</span>
                            </td>
                            <td>{{ $mov->usuario }}</td>
                            <td>
                                <span style="font-size: 11px; color: #64748b;">
                                    {{ $mov->referencia ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty"><i class="fas fa-inbox"></i><p>No hay movimientos en este período</p></div>
    @endif
</div>

<style>
    .filter-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
    .filters { display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }
    .fg { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 160px; }
    .fg label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
    .fg input, .fg select { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; background: #fff; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
    .card-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-title { display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; }
    .card-title i { color: #059669; }
    .badge-info { padding: 5px 12px; background: #ecfdf5; color: #059669; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .table th { background: #f8fafc; padding: 12px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: left; }
    .table td { padding: 14px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background: #f8fafc; }
    .tipo-badge { padding: 4px 10px; border-radius: 100px; font-size: 10px; font-weight: 700; }
    .tipo-badge.entrada { background: #ecfdf5; color: #059669; }
    .tipo-badge.salida { background: #fef3c7; color: #d97706; }
    .tipo-badge.ajuste { background: #f5f3ff; color: #7c3aed; }
    .qty-badge { padding: 4px 12px; border-radius: 100px; font-weight: 800; font-size: 12px; }
    .qty-badge.pos { background: #ecfdf5; color: #059669; }
    .qty-badge.neg { background: #fef2f2; color: #dc2626; }
    .stock-flow { font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #475569; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; }
    .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }
    .empty { padding: 60px 24px; text-align: center; color: #94a3b8; }
    .empty i { font-size: 40px; color: #cbd5e1; margin-bottom: 12px; display: block; }
</style>

@endsection