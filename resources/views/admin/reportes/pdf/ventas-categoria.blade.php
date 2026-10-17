<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #0f172a; font-size: 11px; }

        .header { background: linear-gradient(135deg, #7c3aed, #a855f7); color: #fff; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .header p { font-size: 12px; opacity: 0.9; }
        .header .period { margin-top: 12px; font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.15); display: inline-block; padding: 6px 16px; border-radius: 100px; }

        .meta { background: #f8fafc; padding: 14px 24px; font-size: 11px; color: #64748b; display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }

        .content { padding: 20px 24px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #7c3aed; color: #fff; }
        th { padding: 10px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; text-align: left; }
        th.center { text-align: center; }
        th.right { text-align: right; }
        td { padding: 10px 12px; font-size: 11px; border-bottom: 1px solid #e2e8f0; }
        td.center { text-align: center; }
        td.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .cat-name { font-weight: 700; color: #7c3aed; }
        .total-row { background: #7c3aed !important; color: #fff; font-weight: 800; font-size: 13px; }
        .total-row td { padding: 12px; border-bottom: none; }

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
        <span>Total: {{ count($categorias) }} categorías</span>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th class="center">Ventas</th>
                    <th class="center">Unidades</th>
                    <th class="right">Monto total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $cat)
                    <tr>
                        <td class="cat-name">{{ $cat->nombre }}</td>
                        <td class="center">{{ $cat->total_ventas }}</td>
                        <td class="center">{{ $cat->unidades_vendidas }}</td>
                        <td class="right"><strong>Q{{ number_format($cat->monto_total, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL GENERAL</td>
                    <td class="right">Q{{ number_format($categorias->sum('monto_total'), 2) }}</td>
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