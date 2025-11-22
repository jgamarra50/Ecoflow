<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Reserva #{{ $reservation->id }} - EcoFlow</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #6366f1;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #6366f1;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .receipt-info {
            background: #f9fafb;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .receipt-info h2 {
            color: #6366f1;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #4b5563;
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .info-value {
            color: #111827;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }
        .price-breakdown {
            margin: 30px 0;
        }
        .price-breakdown table {
            width: 100%;
            border-collapse: collapse;
        }
        .price-breakdown th,
        .price-breakdown td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .price-breakdown th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        .price-total {
            font-size: 18px;
            font-weight: bold;
            background: #f3f4f6;
        }
        .price-total td {
            padding-top: 20px;
            color: #6366f1;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .print-button {
            background: #6366f1;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin: 20px auto;
            display: block;
        }
        @media print {
            .print-button {
                display: none;
            }
            .container {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🌱 EcoFlow</div>
            <div class="subtitle">Sistema de Movilidad Compartida</div>
        </div>

        <h1 style="text-align: center; margin-bottom: 30px; color: #111827;">Recibo de Reserva</h1>

        <div class="receipt-info">
            <h2>Información de la Reserva</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Número de Reserva</span>
                    <span class="info-value">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Estado</span>
                    <span class="status-badge status-completed">{{ $reservation->getStatusLabel() }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de emisión</span>
                    <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de creación</span>
                    <span class="info-value">{{ $reservation->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="receipt-info">
            <h2>Información del Cliente</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre</span>
                    <span class="info-value">{{ $reservation->user->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $reservation->user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Teléfono</span>
                    <span class="info-value">{{ $reservation->user->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="receipt-info">
            <h2>Detalles del Vehículo</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Tipo</span>
                    <span class="info-value">{{ ucfirst($reservation->vehicle->type) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Marca y Modelo</span>
                    <span class="info-value">{{ $reservation->vehicle->brand }} {{ $reservation->vehicle->model }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Placa</span>
                    <span class="info-value">{{ $reservation->vehicle->plate }}</span>
                </div>
            </div>
        </div>

        <div class="receipt-info">
            <h2>Período de Reserva</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Fecha de Inicio</span>
                    <span class="info-value">{{ $reservation->getFormattedDates()['start'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Fin</span>
                    <span class="info-value">{{ $reservation->getFormattedDates()['end'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Duración</span>
                    <span class="info-value">{{ $reservation->getDurationInDays() }} día(s)</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Método de Entrega</span>
                    <span class="info-value">
                        @if($reservation->delivery_method === 'pickup')
                            Recogida en estación
                        @else
                            Entrega a domicilio
                        @endif
                    </span>
                </div>
            </div>
            @if($reservation->station)
                <div class="info-item" style="margin-top: 15px;">
                    <span class="info-label">Estación</span>
                    <span class="info-value">{{ $reservation->station->name }} - {{ $reservation->station->address }}</span>
                </div>
            @endif
        </div>

        <div class="price-breakdown">
            <h2 style="margin-bottom: 15px; color: #111827;">Desglose de Precio</h2>
            <table>
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th style="text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @php $breakdown = $reservation->getPriceBreakdown(); @endphp
                    <tr>
                        <td>Alquiler de vehículo ({{ $breakdown['days'] }} día(s) × $50,000 COP)</td>
                        <td style="text-align: right;">${{ number_format($breakdown['base_price'], 0, ',', '.') }} COP</td>
                    </tr>
                    @if($breakdown['delivery_fee'] > 0)
                        <tr>
                            <td>Cargo por entrega a domicilio</td>
                            <td style="text-align: right;">${{ number_format($breakdown['delivery_fee'], 0, ',', '.') }} COP</td>
                        </tr>
                    @endif
                    <tr class="price-total">
                        <td><strong>TOTAL</strong></td>
                        <td style="text-align: right;"><strong>${{ number_format($breakdown['total'], 0, ',', '.') }} COP</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p><strong>EcoFlow - Sistema de Movilidad Compartida</strong></p>
            <p style="margin-top: 8px;">Gracias por usar nuestros servicios de movilidad sostenible</p>
            <p style="margin-top: 8px;">Para consultas: info@ecoflow.com | Tel: +57 300 123 4567</p>
            <p style="margin-top: 15px; font-size: 11px; color: #9ca3af;">
                Este es un documento generado electrónicamente. Conserve este recibo para sus registros.
            </p>
        </div>

        <button class="print-button" onclick="window.print()">🖨️ Imprimir Recibo</button>
    </div>
</body>
</html>
