<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #0f172a; font-size: 11px; }

        .header { background: linear-gradient(135deg, #1e3a8a, #2563eb); color: #fff; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .header p { font-size: 12px; opacity: 0.9; }
        .header .period { margin-top: 12px; font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.15); display: inline-block; padding: 6px 16px; border-radius: 100px; }

        .meta { background: #f8fafc; padding: 14px 24px; font-size: 11px; color: #64748b; display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }

        .stats { display: flex; gap: 1px; background: #e2e8f0; }
        .stat { flex: 1; background: #fff; padding: 16px 20px; text-align: center; }
        .stat-label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .stat-value { font-size: 20px; font-weight: 800; color: #0f172a; }
        .stat-value.green { color: #059669; }

        .content { padding: 20px 24px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #1e3a8a; color: #fff; }
        th { padding: 10px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; text-align: left; }
        th.right { text-align: right; }
        td { padding: 10px 12px; font-size: 11px; border-bottom: 1px solid #e2e8f0; }
        td.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .total-row { background: #1e3a8a !important; color: #fff; font-weight: 800; font-size: 13px; }
        .total-row td { padding: 12px; border-bottom: none; }

        .badge { padding: 3px 8px; background: #eff6ff; color: #1d4ed8; border-radius: 100px; font-size: 10px; font-weight: 700; }

        .footer { margin-top: 30px; padding: 14px 24px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 10px; color: #94a3b8; }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Multiservicios León — Sistema de Gestión</p>
        <div class="period">
            {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} — 
            {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}
        </div>
    </div>

    <div class="meta">
        <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        <span>Total: {{ count($ventas) }} ventas</span>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-label">Total ventas</div>
            <div class="stat-value">{{ $totalVentas }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Monto total</div>
            <div class="stat-value green">Q{{ number_format($montoTotal, 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-label">Promedio por venta</div>
            <div class="stat-value">
                Q{{ $totalVentas > 0 ? number_format($montoTotal / $totalVentas, 2) : '0.00' }}
            </div>
        </div>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th># Venta</th>
                    <th>Fecha</th>
                    <th>Vendedor</th>
                    <th class="right">Subtotal</th>
                    <th class="right">Descuento</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                    <tr>
                        <td><span class="badge">#{{ str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $venta->vendedor }}</td>
                        <td class="right">Q{{ number_format($venta->subtotal, 2) }}</td>
                        <td class="right">{{ $venta->descuento > 0 ? '-Q' . number_format($venta->descuento, 2) : '—' }}</td>
                        <td class="right"><strong>Q{{ number_format($venta->total, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" style="text-align: right;">TOTAL GENERAL</td>
                    <td class="right">Q{{ number_format($montoTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p><strong>Multiservicios León</strong> — Sistema de Gestión de Inventario y Ventas</p>
        <p>Documento generado automáticamente · {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>