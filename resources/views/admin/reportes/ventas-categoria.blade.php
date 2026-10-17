@extends('layouts.admin')

@section('title', 'Ventas por Categoría')
@section('page-title', 'Reporte de Ventas por Categoría')
@section('page-subtitle', 'Distribución de ventas por área de productos')

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
        <a href="{{ route('admin.reportes.ventas-categoria.pdf', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}"
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </form>
</div>

<div class="row-grid">

    {{-- GRÁFICA CIRCULAR --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-chart-pie"></i> Distribución por categoría</div>
        </div>
        <div class="chart-wrap">
            <canvas id="chartCategorias"></canvas>
        </div>
    </div>

    {{-- RESUMEN --}}
    <div class="card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-list"></i> Resumen de ventas</div>
        </div>
        <div class="summary-list">
            @forelse($categorias as $cat)
                <div class="summary-item">
                    <div class="summary-name">{{ $cat->nombre }}</div>
                    <div class="summary-stats">
                        <span class="summary-units">{{ $cat->unidades_vendidas }} uds.</span>
                        <span class="summary-amount">Q{{ number_format($cat->monto_total, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="empty"><i class="fas fa-inbox"></i><p>Sin ventas en este período</p></div>
            @endforelse
        </div>
    </div>

</div>

{{-- GRÁFICA DE BARRAS: PRODUCTOS POR CATEGORÍA --}}
<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-boxes"></i> Productos por categoría</div>
    </div>
    <div class="chart-wrap">
        <canvas id="chartProductos"></canvas>
    </div>
</div>

{{-- TABLA DE VENTAS --}}
<div class="card">
    <div class="card-head">
        <div class="card-title"><i class="fas fa-table"></i> Detalle de ventas por categoría</div>
        <span class="badge-info">{{ $categorias->count() }} categorías</span>
    </div>

    @if($categorias->count() > 0)
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th style="text-align: center;">Ventas</th>
                        <th style="text-align: center;">Unidades</th>
                        <th style="text-align: right;">Monto total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $cat)
                        <tr>
                            <td><strong>{{ $cat->nombre }}</strong></td>
                            <td style="text-align: center;">{{ $cat->total_ventas }}</td>
                            <td style="text-align: center;">{{ $cat->unidades_vendidas }}</td>
                            <td style="text-align: right;"><strong style="color: #059669;">Q{{ number_format($cat->monto_total, 2) }}</strong></td>
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
    // Gráfica circular
    const ctx = document.getElementById('chartCategorias').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($categorias->pluck('nombre')),
            datasets: [{
                data: @json($categorias->pluck('monto_total')),
                backgroundColor: ['#2563eb', '#7c3aed', '#f59e0b', '#ec4899', '#10b981', '#06b6d4'],
                borderWidth: 3,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, font: { size: 12, family: 'Inter' } } }
            }
        }
    });

    // Gráfica de barras productos por categoría
    const ctx2 = document.getElementById('chartProductos').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($productosPorCategoria->pluck('nombre')),
            datasets: [
                {
                    label: 'Productos',
                    data: @json($productosPorCategoria->pluck('total_productos')),
                    backgroundColor: '#2563eb',
                    borderRadius: 8,
                },
                {
                    label: 'Stock total',
                    data: @json($productosPorCategoria->pluck('total_stock')),
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

<style>
    .filter-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 20px; }
    .filters { display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }
    .fg { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .fg label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
    .fg input { padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; outline: none; }
    .row-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; margin-bottom: 20px; }
    .card-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-title { display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; }
    .card-title i { color: #7c3aed; }
    .badge-info { padding: 5px 12px; background: #f5f3ff; color: #7c3aed; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .chart-wrap { padding: 24px; height: 350px; }
    .summary-list { padding: 8px 0; }
    .summary-item { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; border-bottom: 1px solid #f1f5f9; }
    .summary-item:last-child { border-bottom: none; }
    .summary-name { font-size: 14px; font-weight: 600; color: #0f172a; }
    .summary-stats { display: flex; align-items: center; gap: 14px; }
    .summary-units { font-size: 11px; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 100px; font-weight: 700; }
    .summary-amount { font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 800; color: #059669; }
    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 12px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: left; }
    .table td { padding: 14px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background: #f8fafc; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: linear-gradient(135deg, #2563eb, #0ea5e9); color: #fff; }
    .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }
    .empty { padding: 60px 24px; text-align: center; color: #94a3b8; }
    .empty i { font-size: 40px; color: #cbd5e1; margin-bottom: 12px; display: block; }
    @media (max-width: 1000px) { .row-grid { grid-template-columns: 1fr; } }
</style>

@endsection