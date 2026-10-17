@extends('layouts.admin')

@section('title', 'Ventas por Fecha')
@section('page-title', 'Reporte de Ventas por Fecha')
@section('page-subtitle', 'Análisis de ventas en un período')

@section('content')

{{-- FILTROS --}}
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Generar</button>
        <a href="{{ route('admin.reportes.ventas-fecha.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </form>
</div>

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-mini">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="stat-value">{{ $totalVentas }}</div>
            <div class="stat-label">Total ventas</div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-icon" style="background: #ecfdf5; color: #059669;"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-value">Q{{ number_format($montoTotal, 2) }}</div>
            <div class="stat-label">Monto total</div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-icon" style="background: #f5f3ff; color: #7c3aed;"><i class="fas fa-chart-line"></i></div>
        <div>
            <div class="stat-value">Q{{ number_format($promedioDiario, 2) }}</div>
            <div class="stat-label">Promedio diario</div>
        </div>
    </div>
    <div class="stat-mini">
        <div class="stat-icon" style="background: #fef3c7; color: #d97706;"><i class="fas fa-calendar"></i></div>
        <div>
            <div class="stat-value">{{ \Carbon\Carbon::parse($fechaInicio)->diffInDays($fechaFin) + 1 }}</div>
            <div class="stat-label">Días</div>
        </div>
    </div>
</div>

{{-- GRÁFICA DE TENDENCIA --}}
<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-chart-line"></i> Tendencia de ventas por día</div>
        <span class="badge-info">{{ $fechaInicio }} — {{ $fechaFin }}</span>
    </div>
    <div class="chart-wrap">
        <canvas id="chartVentas"></canvas>
    </div>
</div>

{{-- GRÁFICA POR VENDEDOR --}}
@if($ventasPorVendedor->count() > 0)
    <div class="card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-users"></i> Ventas por vendedor</div>
        </div>
        <div class="chart-wrap">
            <canvas id="chartVendedores"></canvas>
        </div>
    </div>
@endif

{{-- TABLA --}}
<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-list"></i> Detalle de ventas</div>
        <span class="badge-info">{{ $totalVentas }} registros</span>
    </div>

    @if($ventas->count() > 0)
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#Venta</th>
                        <th>Fecha</th>
                        <th>Vendedor</th>
                        <th>Subtotal</th>
                        <th>Descuento</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $v)
                        <tr>
                            <td><span class="table-id">#{{ str_pad($v->idVenta, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $v->vendedor }}</td>
                            <td>Q{{ number_format($v->subtotal, 2) }}</td>
                            <td>{{ $v->descuento > 0 ? '-Q' . number_format($v->descuento, 2) : '—' }}</td>
                            <td><strong style="color: #059669;">Q{{ number_format($v->total, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty"><i class="fas fa-inbox"></i><p>No hay ventas en este período</p></div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfica de tendencia
    const ctx = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($ventasPorDia->pluck('fecha')),
            datasets: [{
                label: 'Ventas (Q)',
                data: @json($ventasPorDia->pluck('monto')),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: (v) => 'Q' + v } } }
        }
    });

    @if($ventasPorVendedor->count() > 0)
    // Gráfica de vendedores
    const ctx2 = document.getElementById('chartVendedores').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($ventasPorVendedor->pluck('vendedor')),
            datasets: [{
                label: 'Monto vendido',
                data: @json($ventasPorVendedor->pluck('monto')),
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => 'Q' + parseFloat(ctx.raw).toFixed(2) } }
            },
            scales: { y: { beginAtZero: true, ticks: { callback: (v) => 'Q' + v } } }
        }
    });
    @endif
</script>

<style>
    .filter-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
    .filters { display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }
    .fg { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .fg label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
    .fg input { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; }
    .fg input:focus { border-color: #2563eb; }

    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    .stat-mini { display: flex; align-items: center; gap: 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 19px; }
    .stat-value { font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 800; color: #0f172a; }
    .stat-label { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; margin-bottom: 20px; overflow: hidden; }
    .card-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-title { display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; }
    .card-title i { color: #2563eb; }
    .badge-info { padding: 5px 12px; background: #eff6ff; color: #1d4ed8; border-radius: 100px; font-size: 11px; font-weight: 700; }

    .chart-wrap { padding: 24px; height: 350px; }

    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 12px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: left; }
    .table td { padding: 14px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background: #f8fafc; }
    .table-id { padding: 4px 10px; background: #eff6ff; color: #2563eb; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 700; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; }
    .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }

    .empty { padding: 60px 24px; text-align: center; color: #94a3b8; }
    .empty i { font-size: 40px; color: #cbd5e1; margin-bottom: 12px; display: block; }

    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr; } .filters { flex-direction: column; } .btn { width: 100%; justify-content: center; } }
</style>

@endsection