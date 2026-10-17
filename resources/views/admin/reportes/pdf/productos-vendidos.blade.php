<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #0f172a; font-size: 11px; }

        .header { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .header p { font-size: 12px; opacity: 0.9; }
        .header .period { margin-top: 12px; font-size: 13px; font-weight: 700; background: rgba(255,255,255,0.15); display: inline-block; padding: 6px 16px; border-radius: 100px; }

        .meta { background: #f8fafc; padding: 14px 24px; font-size: 11px; color: #64748b; display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }

        .content { padding: 20px 24px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #d97706; color: #fff; }
        th { padding: 10px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; text-align: left; }
        th.center { text-align: center; }
        th.right { text-align: right; }
        td { padding: 10px 12px; font-size: 11px; border-bottom: 1px solid #e2e8f0; }
        td.center { text-align: center; }
        td.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .rank { display: inline-block; width: 24px; height: 24px; background: #d97706; color: #fff; border-radius: 6px; text-align: center; line-height: 24px; font-weight: 800; font-size: 11px; }
        .rank.gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .prod-name { font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .prod-code { font-family: 'DejaVu Sans Mono', monospace; font-size: 9px; color: #94a3b8; }
        .cat-tag { padding: 3px 8px; background: #f5f3ff; color: #7c3aed; border-radius: 100px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
        .units { padding: 3px 10px; background: #ecfdf5; color: #059669; border-radius: 100px; font-size: 11px; font-weight: 800; }

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
        <span>Total: {{ count($productos) }} productos</span>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th class="center">Unidades</th>
                    <th class="right">Monto total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $index => $prod)
                    <tr>
                        <td><span class="rank {{ $index < 3 ? 'gold' : '' }}">{{ $index + 1 }}</span></td>
                        <td>
                            <div class="prod-name">{{ $prod->nombre }}</div>
                            @if($prod->codigo_interno)
                                <div class="prod-code">{{ $prod->codigo_interno }}</div>
                            @endif
                        </td>
                        <td><span class="cat-tag">{{ $prod->categoria }}</span></td>
                        <td class="center"><span class="units">{{ $prod->total_vendido }}</span></td>
                        <td class="right"><strong>Q{{ number_format($prod->monto_total, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Multiservicios León</strong> — Sistema de Gestión de Inventario y Ventas</p>
        <p>Documento generado automáticamente · {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>