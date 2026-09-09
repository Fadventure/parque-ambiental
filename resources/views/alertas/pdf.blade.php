<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alertas</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #e74c3c;
            font-size: 28px;
            margin: 0;
        }
        .header h2 {
            color: #555;
            font-size: 16px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        .header p {
            color: #777;
            font-size: 13px;
            margin: 5px 0 0 0;
        }
        .fecha {
            text-align: right;
            font-size: 11px;
            color: #777;
            margin-bottom: 15px;
        }
        .info-reporte {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            font-size: 11px;
        }
        .info-reporte strong {
            color: #2c3e50;
        }
        .resumen {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .resumen-item {
            text-align: center;
            flex: 1;
        }
        .resumen-item .numero {
            font-size: 24px;
            font-weight: bold;
        }
        .resumen-item .label {
            font-size: 10px;
            color: #777;
            text-transform: uppercase;
        }
        .resumen-item .numero.rojo { color: #e74c3c; }
        .resumen-item .numero.verde { color: #27ae60; }
        .resumen-item .numero.azul { color: #3498db; }
        .resumen-item .numero.naranja { color: #f39c12; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #2c3e50;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
            vertical-align: top;
        }
        .tipo-emergencia {
            color: #e74c3c;
            font-weight: bold;
        }
        .tipo-normal {
            color: #3498db;
            font-weight: bold;
        }
        .estado-atendido {
            color: #27ae60;
            font-weight: bold;
        }
        .estado-pendiente {
            color: #e74c3c;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-emergencia {
            background: #fee2e2;
            color: #dc2626;
        }
        .badge-normal {
            background: #dbeafe;
            color: #2563eb;
        }
        .badge-atendido {
            background: #dcfce7;
            color: #16a34a;
        }
        .badge-pendiente {
            background: #fef3c7;
            color: #d97706;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .info-adicional {
            margin-top: 10px;
            font-size: 10px;
            color: #777;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 3px;
        }
        .info-adicional span {
            display: inline-block;
            margin-right: 15px;
        }
        .detalle-alerta {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }
        .detalle-alerta strong {
            color: #2c3e50;
        }
        .zona-nombre {
            font-weight: 600;
            color: #2c3e50;
        }
        .usuario-nombre {
            font-weight: 500;
        }
        .page-break {
            page-break-after: always;
        }
        @page {
            margin: 15mm 15mm 20mm 15mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌿 Parque Ambiental</h1>
        <h2>Reporte de Alertas y Emergencias</h2>
        <p>Sistema de Gestión - Invernaderos Inteligentes</p>
    </div>

    <div class="fecha">
        📅 Fecha de generación: {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- INFORMACIÓN DEL REPORTE --}}
    <div class="info-reporte">
        <strong>📋 Información del reporte</strong><br>
        <span>👤 Generado por: <strong>{{ $usuarioReporte->name ?? 'Sistema' }}</strong></span>
        @if($usuarioReporte)
            <span style="margin-left: 15px;">📧 {{ $usuarioReporte->email }}</span>
            <span style="margin-left: 15px;">🏷️ {{ $usuarioReporte->rol === 'admin' ? 'Administrador' : 'Empleado' }}</span>
        @endif
        <br>
        <span>📊 Total de alertas: <strong>{{ $totalAlertas }}</strong></span>
        <span style="margin-left: 15px;">🔴 Emergencias: <strong>{{ $emergencias }}</strong></span>
        <span style="margin-left: 15px;">✅ Atendidas: <strong>{{ $atendidas }}</strong></span>
    </div>

    {{-- RESUMEN --}}
    <div class="resumen">
        <div class="resumen-item">
            <div class="numero azul">{{ $totalAlertas }}</div>
            <div class="label">Total Alertas</div>
        </div>
        <div class="resumen-item">
            <div class="numero rojo">{{ $emergencias }}</div>
            <div class="label">🚨 Emergencias</div>
        </div>
        <div class="resumen-item">
            <div class="numero naranja">{{ $totalAlertas - $emergencias }}</div>
            <div class="label">📋 Normales</div>
        </div>
        <div class="resumen-item">
            <div class="numero verde">{{ $atendidas }}</div>
            <div class="label">✅ Atendidas</div>
        </div>
    </div>

    {{-- TABLA DE ALERTAS --}}
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Tipo</th>
                <th style="width: 12%;">Zona</th>
                <th style="width: 12%;">Reportado por</th>
                <th style="width: 30%;">Mensaje</th>
                <th style="width: 10%;">Estado</th>
                <th style="width: 15%;">Fecha/Hora</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alertas as $alerta)
                <tr>
                    <td>
                        <span class="badge {{ $alerta->tipo == 'Emergencia' ? 'badge-emergencia' : 'badge-normal' }}">
                            {{ $alerta->tipo == 'Emergencia' ? '🚨 Emergencia' : '📋 Normal' }}
                        </span>
                    </td>
                    <td>
                        <span class="zona-nombre">{{ $alerta->zona->nombre ?? 'N/A' }}</span>
                        @if($alerta->zona)
                            <div class="detalle-alerta">
                                🌡️ {{ $alerta->zona->temperatura ?? '--' }}°C · 💧 {{ $alerta->zona->humedad ?? '--' }}%
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="usuario-nombre">{{ $alerta->usuario->name ?? 'Sensor Arduino' }}</span>
                        @if($alerta->usuario)
                            <div class="detalle-alerta">
                                📧 {{ $alerta->usuario->email ?? '' }}
                            </div>
                            <div class="detalle-alerta">
                                🏷️ {{ $alerta->usuario->rol === 'admin' ? 'Admin' : 'Empleado' }}
                            </div>
                        @else
                            <div class="detalle-alerta">
                                🤖 Alerta automática
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $alerta->descripcion }}</strong>
                        <div class="detalle-alerta">
                            🆔 ID: {{ $alerta->id }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $alerta->estado == 'Atendido' ? 'badge-atendido' : 'badge-pendiente' }}">
                            {{ $alerta->estado == 'Atendido' ? '✅ Atendido' : '⏳ Pendiente' }}
                        </span>
                        @if($alerta->estado == 'Atendido')
                            <div class="detalle-alerta" style="color: #16a34a;">
                                ✅ Resuelta
                            </div>
                        @else
                            <div class="detalle-alerta" style="color: #d97706;">
                                ⏳ En espera
                            </div>
                        @endif
                    </td>
                    <td>
                        {{ $alerta->created_at->format('d/m/Y') }}
                        <div class="detalle-alerta">
                            🕐 {{ $alerta->created_at->format('H:i') }}
                        </div>
                        <div class="detalle-alerta" style="color: #777;">
                            ⏱️ Hace {{ $alerta->created_at->diffForHumans() }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #777;">
                        <div style="font-size: 40px; margin-bottom: 10px;">📭</div>
                        No hay alertas registradas en el período seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PIE DE PÁGINA CON INFORMACIÓN ADICIONAL --}}
    <div class="info-adicional">
        <span>📊 Total de alertas: <strong>{{ $totalAlertas }}</strong></span>
        <span>🚨 Emergencias: <strong>{{ $emergencias }}</strong></span>
        <span>✅ Atendidas: <strong>{{ $atendidas }}</strong></span>
        <span>📅 Período: {{ now()->format('d/m/Y') }}</span>
    </div>

    <div class="footer">
        <p>🌿 Parque Ambiental - Sistema de Gestión de Invernaderos Inteligentes</p>
        <p>Reporte generado el {{ now()->format('d/m/Y H:i') }} - {{ now()->format('l') }}</p>
    </div>
</body>
</html>