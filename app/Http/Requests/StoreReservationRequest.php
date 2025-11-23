<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'cliente';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'station_id' => ['required', 'exists:stations,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'total_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Validate vehicle availability
            if (!$this->isVehicleAvailable()) {
                $validator->errors()->add(
                    'vehicle_id',
                    'Este vehículo no está disponible para las fechas seleccionadas.'
                );
            }

            // Validate max duration (e.g., 30 days)
            if ($this->exceedsMaxDuration()) {
                $validator->errors()->add(
                    'end_date',
                    'La duración máxima de una reserva es de 30 días.'
                );
            }

            // Validate that vehicle belongs to selected station
            if (!$this->vehicleBelongsToStation()) {
                $validator->errors()->add(
                    'station_id',
                    'El vehículo seleccionado no pertenece a esta estación.'
                );
            }
        });
    }

    /**
     * Check if vehicle is available for the selected dates
     */
    protected function isVehicleAvailable(): bool
    {
        if (!$this->has(['vehicle_id', 'start_date', 'end_date'])) {
            return false;
        }

        // Check if vehicle exists and is available
        $vehicle = Vehicle::find($this->vehicle_id);
        if (!$vehicle || $vehicle->status !== 'available') {
            return false;
        }

        // Check for conflicting reservations
        $conflicts = Reservation::where('vehicle_id', $this->vehicle_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($q) {
                        $q->where('start_date', '<=', $this->start_date)
                          ->where('end_date', '>=', $this->end_date);
                    });
            })
            ->exists();

        return !$conflicts;
    }

    /**
     * Check if reservation duration exceeds maximum allowed
     */
    protected function exceedsMaxDuration(): bool
    {
        if (!$this->has(['start_date', 'end_date'])) {
            return false;
        }

        $startDate = new \DateTime($this->start_date);
        $endDate = new \DateTime($this->end_date);
        $diff = $startDate->diff($endDate);

        return $diff->days > 30;
    }

    /**
     * Check if vehicle belongs to selected station
     */
    protected function vehicleBelongsToStation(): bool
    {
        if (!$this->has(['vehicle_id', 'station_id'])) {
            return false;
        }

        $vehicle = Vehicle::find($this->vehicle_id);
        return $vehicle && $vehicle->station_id == $this->station_id;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Debes seleccionar un vehículo.',
            'vehicle_id.exists' => 'El vehículo seleccionado no existe.',
            'station_id.required' => 'Debes seleccionar una estación.',
            'station_id.exists' => 'La estación seleccionada no existe.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'total_price.required' => 'El precio total es obligatorio.',
            'total_price.numeric' => 'El precio debe ser un número válido.',
            'total_price.min' => 'El precio no puede ser negativo.',
        ];
    }
}
