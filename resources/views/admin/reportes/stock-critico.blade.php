@extends('layouts.admin')

@section('title', 'Stock Crítico')
@section('page-title', 'Reporte de Stock Crítico')
@section('page-subtitle', 'Productos con stock bajo el mínimo establecido')

@section('content')

<div class="action-bar">
    <div class="alert-box">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <div class="alert-title">{{ $productos->count() }} productos en stock crítico</div>
            <div class="alert-desc">Estos productos requieren reposición inmediata</div>
        </div>
    </div>
    <a href="{{ route('admin.reportes.stock-critico.pdf') }}" class="btn btn-danger" target="_blank">
        <i class="fas fa-file-pdf"></i> Exportar PDF
    </a>
</div>

@if($productos->count() > 0)
    <div class="card">
        <div class="card-head">
            <div class="card-title"><i class="fas fa-list"></i> Productos críticos</div>
            <span class="badge-info">{{ $productos->count() }} productos</span>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th style="text-align: center;">Stock actual</th>
                        <th style="text-align: center;">Stock mínimo</th>
                        <th style="text-align: center;">Faltante</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $prod)
                        <tr>
                            <td>
                                @if($prod->codigo_interno)
                                    <span class="table-id">{{ $prod->codigo_interno }}</span>
                                @else
                                    <span style="color: #cbd5e1;">—</span>
                                @endif
                            </td>
                            <td><strong>{{ $prod->nombre }}</strong></td>
                            <td><span class="cat-badge">{{ $prod->categoria }}</span></td>
                            <td style="text-align: center;">
                                <span class="stock-badge critical">{{ $prod->stock_actual }}</span>
                            </td>
                            <td style="text-align: center;">{{ $prod->stock_minimo }}</td>
                            <td style="text-align: center;">
                                <span class="faltante-badge">{{ $prod->faltante }}</span>
                            </td>
                            <td>
                                <span class="status-badge">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    Crítico
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>¡Todo en orden!</h3>
            <p>No hay productos con stock crítico actualmente</p>
        </div>
    </div>
@endif

<style>
    .action-bar { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
    .alert-box { display: flex; align-items: center; gap: 16px; padding: 16px 20px; background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 12px; flex: 1; }
    .alert-box i { font-size: 28px; color: #dc2626; }
    .alert-title { font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #991b1b; margin-bottom: 4px; }
    .alert-desc { font-size: 12px; color: #b91c1c; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
    .card-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-title { display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; color: #0f172a; }
    .card-title i { color: #dc2626; }
    .badge-info { padding: 5px 12px; background: #fef2f2; color: #dc2626; border-radius: 100px; font-size: 11px; font-weight: 700; }
    .table-wrap { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 12px 20px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: left; }
    .table td { padding: 14px 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .table tbody tr:hover { background: #fef2f2; }
    .table-id { padding: 4px 10px; background: #f1f5f9; color: #475569; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 700; }
    .cat-badge { padding: 4px 10px; background: #f5f3ff; color: #7c3aed; border-radius: 100px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .stock-badge { padding: 5px 12px; border-radius: 100px; font-weight: 800; font-size: 13px; }
    .stock-badge.critical { background: #fef2f2; color: #dc2626; }
    .faltante-badge { padding: 5px 12px; background: #fef3c7; color: #d97706; border-radius: 100px; font-weight: 800; font-size: 13px; }
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: #fef2f2; color: #dc2626; border-radius: 100px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease; }
    .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 6px 16px rgba(220,38,38,0.3); }
    .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(220,38,38,0.45); }
    .empty-state { padding: 80px 24px; text-align: center; }
    .empty-icon { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; }
    .empty-icon.success { background: #ecfdf5; color: #059669; }
    .empty-state h3 { font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
    .empty-state p { font-size: 14px; color: #64748b; }
</style>

@endsection