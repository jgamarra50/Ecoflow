<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        return Reservation::with(['user', 'vehicle'])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Usuario',
            'Email Usuario',
            'Vehículo',
            'Placa',
            'Fecha Inicio',
            'Fecha Fin',
            'Duración (días)',
            'Estado',
            'Precio Total',
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->user->name,
            $reservation->user->email,
            $reservation->vehicle->brand . ' ' . $reservation->vehicle->model,
            $reservation->vehicle->plate,
            $reservation->start_date->format('d/m/Y'),
            $reservation->end_date->format('d/m/Y'),
            $reservation->start_date->diffInDays($reservation->end_date),
            $reservation->getStatusLabel(),
            '$' . number_format($reservation->total_price, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
