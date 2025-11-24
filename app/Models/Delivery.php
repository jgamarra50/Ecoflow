<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'delivery_person_id',
        'type',
        'status',
        'scheduled_time',
        'actual_delivery_time',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'notes',
        'photo_proof',
        'customer_signature',
        'distance_km',
        'delivery_fee',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_delivery_time' => 'datetime',
        'delivery_lat' => 'decimal:7',
        'delivery_lng' => 'decimal:7',
        'distance_km' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
    ];

    // Relationships
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function deliveryPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeForDeliveryPerson($query, $personId)
    {
        return $query->where('delivery_person_id', $personId);
    }

    // Helper methods
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'assigned' => 'Asignada',
            'in_transit' => 'En Camino',
            'arrived' => 'Llegó al Destino',
            'delivered' => 'Entregada',
            'cancelled' => 'Cancelada',
            default => 'Desconocido',
        };
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'assigned' => 'bg-blue-100 text-blue-800 border-blue-300',
            'in_transit' => 'bg-purple-100 text-purple-800 border-purple-300',
            'arrived' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'delivered' => 'bg-green-100 text-green-800 border-green-300',
            'cancelled' => 'bg-red-100 text-red-800 border-red-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function getTypeLabel(): string
    {
        return $this->type === 'delivery' ? 'Entrega' : 'Recogida';
    }

    public function canAssign(): bool
    {
        return $this->status === 'pending';
    }

    public function canStart(): bool
    {
        return $this->status === 'assigned';
    }

    public function canComplete(): bool
    {
        return in_array($this->status, ['in_transit', 'arrived']);
    }

    public function canCancel(): bool
    {
        return !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function assign($deliveryPersonId): bool
    {
        if (!$this->canAssign()) {
            return false;
        }

        $this->delivery_person_id = $deliveryPersonId;
        $this->status = 'assigned';
        return $this->save();
    }

    public function start(): bool
    {
        if (!$this->canStart()) {
            return false;
        }

        $this->status = 'in_transit';
        return $this->save();
    }

    public function markAsArrived(): bool
    {
        if ($this->status !== 'in_transit') {
            return false;
        }

        $this->status = 'arrived';
        return $this->save();
    }

    public function complete(array $data = []): bool
    {
        if (!$this->canComplete()) {
            return false;
        }

        $this->status = 'delivered';
        $this->actual_delivery_time = now();
        
        if (isset($data['notes'])) {
            $this->notes = $data['notes'];
        }
        if (isset($data['photo_proof'])) {
            $this->photo_proof = $data['photo_proof'];
        }
        if (isset($data['customer_signature'])) {
            $this->customer_signature = $data['customer_signature'];
        }
        if (isset($data['distance_km'])) {
            $this->distance_km = $data['distance_km'];
        }

        return $this->save();
    }

    public function cancel(string $reason = null): bool
    {
        if (!$this->canCancel()) {
            return false;
        }

        $this->status = 'cancelled';
        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') . "Cancelación: " . $reason;
        }
        return $this->save();
    }
}
