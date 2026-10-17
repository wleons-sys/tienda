@extends('layouts.vendedor')

@section('title', 'Detalle de Venta')
@section('page-title', 'Venta #' . str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT))
@section('page-subtitle', \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i'))

@section('content')

<div class="detail-card">
    <div class="detail-header {{ $venta->estado === 'Activa' ? 'active' : 'annulled' }}">
        <div class="detail-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div>
            <div class="detail-title">{{ $venta->estado }}</div>
            <div class="detail-desc">Venta {{ $venta->estado === 'Activa' ? 'completada' : 'anulada' }} correctamente</div>
        </div>
    </div>

    <div class="detail-body">
        <div class="info-row"><span>Fecha</span><strong>{{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i') }}</strong></div>
        <div class="info-row"><span>Subtotal</span><strong>Q{{ number_format($venta->subtotal, 2) }}</strong></div>
        <div class="info-row"><span>Descuento</span><strong>Q{{ number_format($venta->descuento, 2) }}</strong></div>
        <div class="info-row total"><span>Total</span><strong>Q{{ number_format($venta->total, 2) }}</strong></div>
    </div>

    <div class="detail-products">
        <h3>Productos</h3>
        <table class="table">
            <thead>
                <tr><th>Producto</th><th>Cantidad</th><th style="text-align: right;">Precio</th><th style="text-align: right;">Total</th></tr>
            </thead>
            <tbody>
                @foreach($detalles as $d)
                    <tr>
                        <td>{{ $d->producto }}</td>
                        <td>{{ $d->cantidad }}</td>
                        <td style="text-align: right;">Q{{ number_format($d->precio_unitario, 2) }}</td>
                        <td style="text-align: right;"><strong>Q{{ number_format($d->total_linea, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="detail-footer">
        <a href="{{ route('vendedor.ventas.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
</div>

<style>
    .detail-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; max-width: 700px; }
    .detail-header { display: flex; gap: 16px; align-items: center; padding: 24px; }
    .detail-header.active { background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-bottom: 3px solid #10b981; }
    .detail-header.annulled { background: linear-gradient(135deg, #fef2f2, #fecaca); border-bottom: 3px solid #ef4444; }
    .detail-icon { width: 56px; height: 56px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; background: linear-gradient(135deg, #059669, #10b981); }
    .detail-title { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .detail-desc { font-size: 13px; color: #64748b; }

    .detail-body { padding: 8px 24px 20px; }
    .info-row { display: flex; justify-content: space-between; padding: 14px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .info-row span { color: #64748b; }
    .info-row strong { color: #0f172a; }
    .info-row.total { border-bottom: none; padding-top: 20px; border-top: 2px solid #e2e8f0; margin-top: 8px; }
    .info-row.total strong { color: #059669; font-size: 22px; font-family: 'Poppins', sans-serif; font-weight: 800; }

    .detail-products { padding: 0 24px 20px; }
    .detail-products h3 { font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 700; margin-bottom: 12px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th { background: #f8fafc; padding: 12px 16px; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: left; }
    .table td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid #f1f5f9; }

    .detail-footer { padding: 20px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc; }
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 18px; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600; text-decoration: none; }
    .btn-ghost { background: #fff; border: 1.5px solid #e2e8f0; color: #475569; }
</style>

@endsection