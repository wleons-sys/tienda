<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #0f172a; font-size: 10px; }

        .header { background: linear-gradient(135deg, #059669, #10b981); color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .header h1 { font-size: 20px; font-weight: 800; margin-bottom: 6px; }
        .header p { font-size: 11px; opacity: 0.9; }
        .header .period { margin-top: 10px; font-size: 12px; font-weight: 700; background: rgba(255,255,255,0.15); display: inline-block; padding: 5px 14px; border-radius: 100px; }

        .meta { background: #f8fafc; padding: 12px 20px; font-size: 10px; color: #64748b; display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }

        .content { padding: 16px 20px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #059669; color: #fff; }
        th { padding: 8px 10px; font-size: 9px; font-weight: 700; text-transform: uppercase; text-align: left; }
        th.center { text-align: center; }
        td { padding: 8px 10px; font-size: 10px; border-bottom: 1px solid #e2e8f0; }
        td.center { text-align: center; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .tipo { padding: 3px 8px; border-radius: 100px; font-size: 9px; font-weight: 700; }
        .tipo.entrada { background: #ecfdf5; color: #059669; }
        .tipo.salida { background: #fef3c7; color: #d97706; }
        .tipo.ajuste { background: #f5f3ff; color: #7c3aed; }

        .qty { padding: 2px 8px; border-radius: 100px; font-weight: 800; }
        .qty.pos { background: #ecfdf5; color: #059669; }
        .qty.neg { background: #fef2f2; color: #dc2626; }

        .stock { font-family: 'DejaVu Sans Mono', monospace; font-size: 10px; font-weight: 700; color: #475569; }

        .footer { margin-top: 24px; padding: 12px 20px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 9px; color: #94a3b8; }
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
            @if($tipo) · Tipo: {{ $tipo }} @endif
        </div>
    </div>

    <div class="meta">
        <span>Generado: {{ now()->format('d/m/Y H:i') }}</span>
        <span>Total: {{ count($movimientos) }} movimientos</span>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th class="center">Tipo</th>
                    <th class="center">Cantidad</th>
                    <th class="center">Stock</th>
                    <th>Usuario</th>
                    <th>Referencia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $mov)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $mov->producto }}</strong></td>
                        <td class="center">
                            <span class="tipo {{ strtolower($mov->tipo) }}">
                                {{ $mov->tipo }}
                            </span>
                        </td>
                        <td class="center">
                            <span class="qty {{ $mov->cantidad > 0 ? 'pos' : 'neg' }}">
                                {{ $mov->cantidad > 0 ? '+' : '' }}{{ $mov->cantidad }}
                            </span>
                        </td>
                        <td class="center">
                            <span class="stock">{{ $mov->stock_anterior }} → {{ $mov->stock_nuevo }}</span>
                        </td>
                        <td>{{ $mov->usuario }}</td>
                        <td>{{ $mov->referencia ?? '—' }}</td>
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