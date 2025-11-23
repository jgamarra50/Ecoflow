<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationships
     */
    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class);
    }

    /**
     * Assign default role to user on creation
     */
    public function assignDefaultRole(string $roleName = 'cliente'): void
    {
        if (!$this->hasAnyRole(['admin', 'cliente', 'operador', 'tecnico'])) {
            $this->assignRole($roleName);
            $this->role = $roleName;
            $this->save();
        }
    }

    /**
     * Helper methods
     */
    public function isActive()
    {
        return $this->is_active;
    }

    public function getStatusLabel()
    {
        return $this->is_active ? 'Activo' : 'Inactivo';
    }

    public function getInitials()
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }
}
