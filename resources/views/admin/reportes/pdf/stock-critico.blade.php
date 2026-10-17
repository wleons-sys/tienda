<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock Crítico</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #0f172a; font-size: 11px; }

        .header { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; padding: 24px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .header p { font-size: 12px; opacity: 0.9; }

        .meta { background: #f8fafc; padding: 14px 24px; font-size: 11px; color: #64748b; display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }

        .alert { background: #fef2f2; padding: 12px 20px; border-left: 4px solid #dc2626; margin: 16px 24px; border-radius: 8px; }
        .alert-title { font-weight: 800; color: #991b1b; font-size: 12px; margin-bottom: 3px; }
        .alert-desc { font-size: 11px; color: #b91c1c; }

        .content { padding: 20px 24px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #dc2626; color: #fff; }
        th { padding: 10px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; text-align: left; }
        th.center { text-align: center; }
        td { padding: 10px 12px; font-size: 11px; border-bottom: 1px solid #e2e8f0; }
        td.center { text-align: center; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .prod-name { font-weight: 700; color: #0f172a; }
        .prod-code { font-family: 'DejaVu Sans Mono', monospace; font-size: 9px; color: #94a3b8; }
        .cat-tag { padding: 3px 8px; background: #f5f3ff; color: #7c3aed; border-radius: 100px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
        .stock-critical { padding: 3px 10px; background: #fef2f2; color: #dc2626; border-radius: 100px; font-size: 11px; font-weight: 800; }
        .faltante { padding: 3px 10px; background: #fef3c7; color: #d97706; border-radius: 100px; font-size: 11px; font-weight: 800; }

        .footer { margin-top: 30px; padding: 14px 24px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 10px; color: #94a3b8; }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Stock Crítico</h1>
        <p>Multiservicios León — Sistema de Gestión</p>
    </div>

    <div class="meta">
        <span>Generado: {{ $fecha }}</span>
        <span>Total: {{ count($productos) }} productos</span>
    </div>

    <div class="alert">
        <div class="alert-title">⚠️ {{ count($productos) }} productos requieren reposición</div>
        <div class="alert-desc">Estos productos tienen stock por debajo del mínimo establecido</div>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th class="center">Stock actual</th>
                    <th class="center">Stock mínimo</th>
                    <th class="center">Faltante</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $prod)
                    <tr>
                        <td>
                            @if($prod->codigo_interno)
                                <span class="prod-code">{{ $prod->codigo_interno }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="prod-name">{{ $prod->nombre }}</span></td>
                        <td><span class="cat-tag">{{ $prod->categoria }}</span></td>
                        <td class="center"><span class="stock-critical">{{ $prod->stock_actual }}</span></td>
                        <td class="center">{{ $prod->stock_minimo }}</td>
                        <td class="center"><span class="faltante">+{{ $prod->faltante }}</span></td>
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