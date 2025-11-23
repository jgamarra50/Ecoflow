<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Reservas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #111;
        }
        h2 {
            color: #4F46E5;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚴 EcoFlow - Reporte de Reservas</h1>
        <p>Período: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Total Reservas</div>
            <div class="stat-value">{{ $generalStats['total_reservations'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Ingresos Totales</div>
            <div class="stat-value">${{ number_format($generalStats['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Vehículos Activos</div>
            <div class="stat-value">{{ $generalStats['total_vehicles'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Duración Promedio</div>
            <div class="stat-value">{{ $generalStats['avg_rental_duration'] }} días</div>
        </div>
    </div>

    <h2>Top 10 Vehículos Más Rentados</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Vehículo</th>
                <th>Placa</th>
                <th>Total Reservas</th>
                <th>Ingresos Generados</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topVehicles as $index => $vehicle)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                    <td>{{ $vehicle->plate }}</td>
                    <td>{{ $vehicle->reservations_count }}</td>
                    <td>${{ number_format($vehicle->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Estados de Reservas</h2>
    <table>
        <thead>
            <tr>
                <th>Estado</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Completadas</td>
                <td>{{ $statusStats['completed'] }}</td>
            </tr>
            <tr>
                <td>Activas</td>
                <td>{{ $statusStats['active'] }}</td>
            </tr>
            <tr>
                <td>Canceladas</td>
                <td>{{ $statusStats['cancelled'] }}</td>
            </tr>
            <tr>
                <td>Pendientes</td>
                <td>{{ $statusStats['pending'] }}</td>
            </tr>
            <tr>
                <td>Confirmadas</td>
                <td>{{ $statusStats['confirmed'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>EcoFlow - Sistema de Gestión de Vehículos Eléctricos</p>
    </div>
</body>
</html>
