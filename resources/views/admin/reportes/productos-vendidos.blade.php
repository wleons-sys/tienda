@extends('layouts.admin')

@section('title', 'Productos Más Vendidos')
@section('page-title', 'Top 10 Productos Más Vendidos')
@section('page-subtitle', 'Productos con mayor rotación en el período')

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
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Generar</button>
        <a href="{{ route('admin.reportes.productos-vendidos.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </form>
</div>

<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-trophy"></i> Top 10 productos más vendidos</div>
        <span class="badge-info">{{ $fechaInicio }} — {{ $fechaFin }}</span>
    </div>
    <div class="chart-wrap">
        <canvas id="chartProductos"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-list"></i> Ranking completo</div>
        <span class="badge-info">{{ $productos->count() }} productos</span>
    </div>

    @if($productos->count() > 0)
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th style="text-align: center;">Unidades</th>
                        <th style="text-align: right;">Monto total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $index => $prod)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $index < 3 ? 'rank-top' : '' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div class="prod-info">
                                    <div class="prod-name">{{ $prod->nombre }}</div>
                                    @if($prod->codigo_interno)
                                        <div class="prod-code">{{ $prod->codigo_interno }}</div>
                                    @endif
                                </div>
                            </td>
                            <td><span class="cat-badge">{{ $prod->categoria }}</span></td>
                            <td style="text-align: center;">
                                <span class="units-badge">{{ $prod->total_vendido }}</span>
                            </td>
                            <td style="text-align: right;">
                                <strong style="color: #059669;">Q{{ number_format($prod->monto_total, 2) }}</strong>
                            </td>
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
    const ctx = document.getElementById('chartProductos').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($productos->pluck('nombre')),
            datasets: [{
                label: 'Unidades vendidas',
                data: @json($productos->pluck('total_vendido')),
                backgroundColor: 'rgba(37, 99, 235, 0.8)',
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => ctx.raw + ' unidades' } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });
</script>

<style>
    .filter-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
    .filters { display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }
    .fg { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .fg label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
    .fg input { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; margin-bottom: 20px; overflow: hidden; }
    .card-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-title { display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; }
    .card-title i { color: #d97706; }
    .badge-info { padding: 5px 12px; background: #fef3c7; color: #d97706; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .chart-wrap { padding: 24px; height: 400px; }
    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 12px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: left; }
    .table td { padding: 14px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background: #f8fafc; }
    .rank-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #f1f5f9; color: #64748b; border-radius: 8px; font-weight: 800; font-size: 12px; }
    .rank-badge.rank-top { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .prod-info { display: flex; flex-direction: column; gap: 2px; }
    .prod-name { font-size: 13px; font-weight: 700; color: #0f172a; }
    .prod-code { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: #94a3b8; }
    .cat-badge { padding: 4px 10px; background: #f5f3ff; color: #7c3aed; border-radius: 100px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .units-badge { padding: 4px 12px; background: #ecfdf5; color: #059669; border-radius: 100px; font-size: 12px; font-weight: 800; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; }
    .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }
    .empty { padding: 60px 24px; text-align: center; color: #94a3b8; }
    .empty i { font-size: 40px; color: #cbd5e1; margin-bottom: 12px; display: block; }
</style>

@endsection