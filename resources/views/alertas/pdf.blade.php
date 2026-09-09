<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alertas</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #e74c3c; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #e74c3c; font-size: 24px; margin: 0; }
        .header p { color: #777; font-size: 14px; margin: 5px 0 0 0; }
        .fecha { text-align: right; font-size: 11px; color: #777; margin-bottom: 15px; }
        .resumen { display: flex; justify-content: space-between; margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .resumen-item { text-align: center; }
        .resumen-item .numero { font-size: 22px; font-weight: bold; }
        .resumen-item .label { font-size: 10px; color: #777; text-transform: uppercase; }
        .resumen-item .numero.rojo { color: #e74c3c; }
        .resumen-item .numero.verde { color: #27ae60; }
        .resumen-item .numero.azul { color: #3498db; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #2c3e50; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px 10px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .tipo-emergencia { color: #e74c3c; font-weight: bold; }
        .tipo-normal { color: #3498db; font-weight: bold; }
        .estado-atendido { color: #27ae60; font-weight: bold; }
        .estado-pendiente { color: #e74c3c; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌿 Parque Ambiental</h1>
        <p>Reporte de Alertas y Emergencias</p>
    </div>

    <div class="fecha">
        Fecha de generación: {{ now()->format('d/m/Y H:i') }}
    </div>

    <div class="resumen">
        <div class="resumen-item">
            <div class="numero azul">{{ $totalAlertas }}</div>
            <div class="label">Total Alertas</div>
        </div>
        <div class="resumen-item">
            <div class="numero rojo">{{ $emergencias }}</div>
            <div class="label">Emergencias</div>
        </div>
        <div class="resumen-item">
            <div class="numero verde">{{ $atendidas }}</div>
            <div class="label">Atendidas</div>
        </div>
    </div>

    <table>
        <thead>
            <tr><th>Tipo</th><th>Zona</th><th>Origen</th><th>Mensaje</th><th>Estado</th><th>Fecha</th></tr>
        </thead>
        <tbody>
            @forelse($alertas as $alerta)
                <tr>
                    <td><span class="{{ $alerta->tipo == 'Emergencia' ? 'tipo-emergencia' : 'tipo-normal' }}">{{ $alerta->tipo }}</span></td>
                    <td>{{ $alerta->zona->nombre ?? 'N/A' }}</td>
                    <td>{{ $alerta->usuario ? 'Empleado' : 'Sensor Arduino' }}</td>
                    <td>{{ $alerta->descripcion }}</td>
                    <td><span class="{{ $alerta->estado == 'Atendido' ? 'estado-atendido' : 'estado-pendiente' }}">{{ $alerta->estado }}</span></td>
                    <td>{{ $alerta->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center; color: #777;">No hay alertas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Reporte generado desde el Sistema Parque Ambiental - {{ now()->format('Y') }}
    </div>
</body>
</html>