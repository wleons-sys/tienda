@extends('layouts.admin')

@section('title', 'Detalle de Movimiento')
@section('page-title', 'Detalle de Movimiento')
@section('page-subtitle', 'Movimiento #' . str_pad($movimiento->idMovimiento, 5, '0', STR_PAD_LEFT))

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ route('admin.movimientos.index') }}" class="breadcrumb-link">
        <i class="fas fa-exchange-alt"></i> Movimientos
    </a>
    <i class="fas fa-chevron-right breadcrumb-sep"></i>
    <span class="breadcrumb-current">#{{ str_pad($movimiento->idMovimiento, 5, '0', STR_PAD_LEFT) }}</span>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="detail-layout">

    {{-- Columna principal --}}
    <div class="detail-main">

        {{-- Card de movimiento --}}
        <div class="card">
            <div class="mov-header
                {{ $movimiento->anulado ? 'header-annulled' :
                   ($movimiento->tipo === 'Entrada' ? 'header-entrada' :
                   ($movimiento->tipo === 'Salida' ? 'header-salida' : 'header-ajuste')) }}">

                <div class="mov-icon">
                    @if($movimiento->tipo === 'Entrada')
                        <i class="fas fa-arrow-down"></i>
                    @elseif($movimiento->tipo === 'Salida')
                        <i class="fas fa-arrow-up"></i>
                    @else
                        <i class="fas fa-sliders-h"></i>
                    @endif
                </div>

                <div class="mov-header-info">
                    <div class="mov-type">{{ $movimiento->tipo }}</div>
                    <div class="mov-product">{{ $movimiento->producto_nombre }}</div>
                    <div class="mov-meta">
                        @if($movimiento->anulado)
                            <span class="mov-badge mov-badge-red">
                                <i class="fas fa-ban"></i> Anulado
                            </span>
                        @else
                            <span class="mov-badge mov-badge-green">
                                <i class="fas fa-check"></i> Activo
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mov-body">

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-hashtag"></i> ID Movimiento</span>
                    <span class="info-value info-value-mono">
                        #{{ str_pad($movimiento->idMovimiento, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-box"></i> Producto</span>
                    <span class="info-value">{{ $movimiento->producto_nombre }}</span>
                </div>

                @if($movimiento->codigo_interno)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-barcode"></i> Código</span>
                        <span class="info-value info-value-mono">{{ $movimiento->codigo_interno }}</span>
                    </div>
                @endif

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-cubes"></i> Cantidad</span>
                    <span class="info-value">
                        <span class="qty-big {{ $movimiento->cantidad > 0 ? 'qty-pos' : 'qty-neg' }}">
                            {{ $movimiento->cantidad > 0 ? '+' : '' }}{{ $movimiento->cantidad }} unidades
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-chart-line"></i> Cambio de stock</span>
                    <span class="info-value">
                        <div class="stock-change">
                            <span class="stock-before">{{ $movimiento->stock_anterior }}</span>
                            <i class="fas fa-arrow-right"></i>
                            <span class="stock-after">{{ $movimiento->stock_nuevo }}</span>
                        </div>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user"></i> Usuario</span>
                    <span class="info-value">
                        <span class="user-chip">
                            <span class="user-chip-avatar">{{ strtoupper(substr($movimiento->usuario_nombre, 0, 1)) }}</span>
                            {{ $movimiento->usuario_nombre }}
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-envelope"></i> Correo</span>
                    <span class="info-value info-value-small">{{ $movimiento->usuario_email }}</span>
                </div>

                @if($movimiento->referencia)
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-tag"></i> Referencia</span>
                        <span class="info-value">{{ $movimiento->referencia }}</span>
                    </div>
                @endif

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar"></i> Fecha y hora</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($movimiento->created_at)->format('d/m/Y H:i') }} hrs
                    </span>
                </div>

            </div>
        </div>

    </div>

    {{-- Columna lateral --}}
    <div class="detail-side">

        {{-- Estado visual --}}
        <div class="card">
            <div class="status-visual
                {{ $movimiento->anulado ? 'status-annulled' :
                   ($movimiento->tipo === 'Entrada' ? 'status-entrada' :
                   ($movimiento->tipo === 'Salida' ? 'status-salida' : 'status-ajuste')) }}">

                <div class="status-visual-icon">
                    @if($movimiento->anulado)
                        <i class="fas fa-ban"></i>
                    @elseif($movimiento->tipo === 'Entrada')
                        <i class="fas fa-arrow-down"></i>
                    @elseif($movimiento->tipo === 'Salida')
                        <i class="fas fa-arrow-up"></i>
                    @else
                        <i class="fas fa-sliders-h"></i>
                    @endif
                </div>

                <div class="status-visual-label">
                    {{ $movimiento->anulado ? 'Movimiento Anulado' : $movimiento->tipo . ' de Inventario' }}
                </div>

                <div class="status-visual-value {{ $movimiento->cantidad > 0 ? 'val-pos' : 'val-neg' }}">
                    {{ $movimiento->cantidad > 0 ? '+' : '' }}{{ $movimiento->cantidad }}
                </div>

                <div class="status-visual-desc">
                    @if($movimiento->anulado)
                        El stock fue restaurado a {{ $movimiento->stock_anterior }} unidades
                    @elseif($movimiento->tipo === 'Entrada')
                        Stock incrementado correctamente
                    @elseif($movimiento->tipo === 'Salida')
                        Stock disminuido correctamente
                    @else
                        Stock ajustado manualmente
                    @endif
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="card">
            <div class="card-head">
                <div class="card-head-title">
                    <i class="fas fa-cog"></i> Acciones
                </div>
            </div>

            <div class="actions-body">
                <a href="{{ route('admin.movimientos.index') }}" class="btn btn-ghost btn-block">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>

                @if(!$movimiento->anulado)
                    <form action="{{ route('admin.movimientos.destroy', $movimiento->idMovimiento) }}"
                          method="POST"
                          onsubmit="return confirm('¿Anular este movimiento? El stock será restaurado a {{ $movimiento->stock_anterior }} unidades.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-ban"></i> Anular Movimiento
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>

</div>

<style>
    .breadcrumb { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 13px; }
    .breadcrumb-link { display: inline-flex; align-items: center; gap: 7px; color: #2563eb; text-decoration: none; font-weight: 600; }
    .breadcrumb-link:hover { color: #1e40af; }
    .breadcrumb-link i { font-size: 12px; }
    .breadcrumb-sep { color: #cbd5e1; font-size: 10px; }
    .breadcrumb-current { color: #64748b; font-weight: 600; font-family: 'JetBrains Mono', monospace; }

    .alert { display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 500; }
    .alert-success { background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; }
    .alert i { font-size: 16px; }

    .detail-layout { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; align-items: start; }
    .detail-main, .detail-side { display: flex; flex-direction: column; gap: 20px; }

    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }

    /* Mov header */
    .mov-header { display: flex; align-items: center; gap: 18px; padding: 24px; border-bottom: 1px solid #f1f5f9; }
    .header-entrada { background: linear-gradient(135deg, #ecfdf5, #d1fae5); }
    .header-salida { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .header-ajuste { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
    .header-annulled { background: linear-gradient(135deg, #fef2f2, #fecaca); }

    .mov-icon { width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 26px; color: #fff; flex-shrink: 0; }
    .header-entrada .mov-icon { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 12px 24px -8px rgba(16,185,129,0.4); }
    .header-salida .mov-icon { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 12px 24px -8px rgba(245,158,11,0.4); }
    .header-ajuste .mov-icon { background: linear-gradient(135deg, #7c3aed, #a855f7); box-shadow: 0 12px 24px -8px rgba(139,92,246,0.4); }
    .header-annulled .mov-icon { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 12px 24px -8px rgba(220,38,38,0.4); }

    .mov-header-info { flex: 1; }
    .mov-type { font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px; }
    .header-entrada .mov-type { color: #059669; }
    .header-salida .mov-type { color: #d97706; }
    .header-ajuste .mov-type { color: #7c3aed; }
    .header-annulled .mov-type { color: #dc2626; }

    .mov-product { font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.5px; }
    .mov-meta { display: flex; gap: 8px; flex-wrap: wrap; }
    .mov-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 100px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .mov-badge-green { background: #ecfdf5; color: #059669; }
    .mov-badge-red { background: #fef2f2; color: #dc2626; }

    /* Body */
    .mov-body { padding: 8px 24px 20px; }
    .info-row { display: grid; grid-template-columns: 180px 1fr; gap: 20px; padding: 16px 0; border-bottom: 1px solid #f1f5f9; align-items: center; }
    .info-row:last-child { border-bottom: none; }

    .info-label { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; }
    .info-label i { color: #94a3b8; font-size: 13px; width: 16px; }
    .info-value { font-size: 14px; font-weight: 500; color: #0f172a; }
    .info-value-mono { font-family: 'JetBrains Mono', monospace; background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-block; }
    .info-value-small { font-size: 12px; color: #64748b; }

    .qty-big { font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 800; padding: 6px 14px; border-radius: 100px; }
    .qty-pos { background: #ecfdf5; color: #059669; }
    .qty-neg { background: #fef2f2; color: #dc2626; }

    .stock-change { display: inline-flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; }
    .stock-before { font-size: 15px; font-weight: 600; color: #94a3b8; }
    .stock-change i { color: #cbd5e1; font-size: 12px; }
    .stock-after { font-size: 17px; font-weight: 800; color: #0f172a; }

    .user-chip { display: inline-flex; align-items: center; gap: 8px; padding: 4px 12px 4px 4px; background: #f5f3ff; border-radius: 100px; font-size: 12px; font-weight: 700; color: #6d28d9; }
    .user-chip-avatar { width: 24px; height: 24px; background: linear-gradient(135deg, #7c3aed, #a855f7); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; }

    /* Status visual */
    .status-visual { padding: 32px 24px; text-align: center; }
    .status-entrada { background: linear-gradient(135deg, #ecfdf5, #d1fae5); }
    .status-salida { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .status-ajuste { background: linear-gradient(135deg, #f5f3ff, #ede9fe); }
    .status-annulled { background: linear-gradient(135deg, #fef2f2, #fecaca); }

    .status-visual-icon { width: 68px; height: 68px; margin: 0 auto 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #fff; }
    .status-entrada .status-visual-icon { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 12px 24px -8px rgba(16,185,129,0.4); }
    .status-salida .status-visual-icon { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 12px 24px -8px rgba(245,158,11,0.4); }
    .status-ajuste .status-visual-icon { background: linear-gradient(135deg, #7c3aed, #a855f7); box-shadow: 0 12px 24px -8px rgba(139,92,246,0.4); }
    .status-annulled .status-visual-icon { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 12px 24px -8px rgba(220,38,38,0.4); }

    .status-visual-label { font-size: 12px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #64748b; margin-bottom: 8px; }
    .status-visual-value { font-family: 'Poppins', sans-serif; font-size: 48px; font-weight: 900; line-height: 1; margin-bottom: 12px; letter-spacing: -2px; }
    .val-pos { color: #059669; }
    .val-neg { color: #dc2626; }
    .status-visual-desc { font-size: 13px; color: #64748b; line-height: 1.5; max-width: 240px; margin: 0 auto; }

    /* Card head */
    .card-head { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; }
    .card-head-title { display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700; color: #0f172a; }
    .card-head-title i { color: #2563eb; font-size: 14px; }

    .actions-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 10px; }

    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 11px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s ease; justify-content: center; }
    .btn-block { width: 100%; }
    .btn-ghost { background: #f1f5f9; color: #475569; }
    .btn-ghost:hover { background: #e2e8f0; }
    .btn-danger { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 6px 16px rgba(220,38,38,0.3); }
    .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(220,38,38,0.45); }

    @media (max-width: 1000px) { .detail-layout { grid-template-columns: 1fr; } }
    @media (max-width: 640px) {
        .info-row { grid-template-columns: 1fr; gap: 6px; }
        .mov-body { padding: 8px 16px 16px; }
        .mov-header { padding: 18px; }
        .mov-product { font-size: 17px; }
        .status-visual-value { font-size: 38px; }
    }
</style>

@endsection