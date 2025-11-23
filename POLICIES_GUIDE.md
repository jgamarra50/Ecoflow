# Authorization Policies - Guía de Uso

## Policies Implementadas

### 1. **VehiclePolicy**
Control de acceso a vehículos.

**Permisos:**
- ✅ **create**: Solo admins
- ✅ **update**: Admins y técnicos
- ✅ **delete**: Solo admins
- ✅ **performMaintenance**: Admins y técnicos
- ✅ **changeStatus**: Admins, técnicos y operadores

**Uso en Controladores:**
```php
// Verificar permisos antes de acciones
public function update(Request $request, Vehicle $vehicle)
{
    $this->authorize('update', $vehicle);
    
    // Actualizar vehículo...
}

// Verificar permisos personalizados
public function performMaintenance(Vehicle $vehicle)
{
    $this->authorize('performMaintenance', $vehicle);
    
    // Realizar mantenimiento...
}
```

**Uso en Livewire:**
```php
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VehiclesManager extends Component
{
    use AuthorizesRequests;
    
    public function editVehicle($vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);
        $this->authorize('update', $vehicle);
        
        // Editar vehículo...
    }
}
```

**Uso en Vistas Blade:**
```blade
@can('update', $vehicle)
    <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn">Editar</a>
@endcan

@can('delete', $vehicle)
    <button wire:click="deleteVehicle({{ $vehicle->id }})" class="btn-danger">
        Eliminar
    </button>
@endcan

@cannot('update', $vehicle)
    <p class="text-gray-500">No tienes permiso para editar este vehículo</p>
@endcannot
```

---

### 2. **ReservationPolicy**
Control de acceso a reservaciones.

**Permisos:**
- ✅ **view**: Usuario dueño, admins y operadores
- ✅ **create**: Solo clientes
- ✅ **update**: Admins y operadores
- ✅ **cancel**: Usuario dueño (si no está cancelada/completada)
- ✅ **delete**: Solo admins

**Uso en Controladores:**
```php
public function show(Reservation $reservation)
{
    $this->authorize('view', $reservation);
    
    return view('reservations.show', compact('reservation'));
}

public function cancel(Reservation $reservation)
{
    $this->authorize('cancel', $reservation);
    
    $reservation->update(['status' => 'cancelled']);
}
```

**Uso en Vistas:**
```blade
@can('view', $reservation)
    <a href="{{ route('reservations.show', $reservation) }}">Ver Detalles</a>
@endcan

@can('cancel', $reservation)
    <button wire:click="cancelReservation({{ $reservation->id }})">
        Cancelar Reserva
    </button>
@endcan

{{-- Mostrar diferentes opciones según permiso --}}
@if(Auth::user()->can('update', $reservation))
    <button>Modificar Estado</button>
@elseif(Auth::user()->can('cancel', $reservation))
    <button>Cancelar Mi Reserva</button>
@endif
```

---

### 3. **MaintenancePolicy**
Control de tickets de mantenimiento.

**Permisos:**
- ✅ **viewAny**: Admins, técnicos y operadores
- ✅ **view**: Admins/operadores ven todos, técnicos solo asignados
- ✅ **update**: Admins actualizan todos, técnicos solo asignados
- ✅ **assign**: Solo admins
- ✅ **complete**: Admins completan todos, técnicos solo asignados

**Uso en Controladores:**
```php
public function update(Request $request, Maintenance $maintenance)
{
    $this->authorize('update', $maintenance);
    
    $maintenance->update($request->validated());
}

public function assignTechnician(Maintenance $maintenance, User $technician)
{
    $this->authorize('assign', $maintenance);
    
    $maintenance->update(['assigned_to' => $technician->id]);
}
```

**Uso en Livewire:**
```php
public function completeMaintenance($maintenanceId)
{
    $maintenance = Maintenance::find($maintenanceId);
    $this->authorize('complete', $maintenance);
    
    $maintenance->update(['status' => 'completed']);
}
```

**Uso en Vistas:**
```blade
@can('update', $maintenance)
    <button wire:click="editMaintenance({{ $maintenance->id }})">
        Actualizar Ticket
    </button>
@endcan

@can('assign', $maintenance)
    <select wire:model="selectedTechnician">
        @foreach($technicians as $tech)
            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
        @endforeach
    </select>
@endcan

@can('complete', $maintenance)
    <button wire:click="completeMaintenance({{ $maintenance->id }})">
        Marcar como Completado
    </button>
@endcan
```

---

### 4. **UserPolicy**
Gestión de usuarios.

**Permisos:**
- ✅ **viewAny**: Solo admins
- ✅ **view**: Usuario mismo o admin
- ✅ **create**: Solo admins
- ✅ **update**: Usuario mismo o admin
- ✅ **delete**: Solo admins (no pueden eliminarse a sí mismos)
- ✅ **changeRole**: Solo admins (no pueden cambiar su propio rol)
- ✅ **suspend**: Solo admins (no pueden suspenderse a sí mismos)

**Uso en Controladores:**
```php
public function index()
{
    $this->authorize('viewAny', User::class);
    
    $users = User::all();
    return view('users.index', compact('users'));
}

public function changeRole(User $user, Request $request)
{
    $this->authorize('changeRole', $user);
    
    $user->update(['role' => $request->role]);
}
```

**Uso en Vistas:**
```blade
@can('viewAny', App\Models\User::class)
    <a href="{{ route('admin.users') }}" class="nav-link">
        Gestionar Usuarios
    </a>
@endcan

@can('changeRole', $user)
    <select wire:model="userRole">
        <option value="cliente">Cliente</option>
        <option value="tecnico">Técnico</option>
        <option value="operador">Operador</option>
        <option value="admin">Admin</option>
    </select>
@endcan

@can('delete', $user)
    <button wire:click="deleteUser({{ $user->id }})">Eliminar</button>
@else
    <span class="text-gray-400">No se puede eliminar</span>
@endcan
```

---

## Directivas Blade Disponibles

```blade
{{-- Verificar un permiso --}}
@can('update', $model)
    <!-- Código si tiene permiso -->
@endcan

{{-- Verificar falta de permiso --}}
@cannot('delete', $model)
    <!-- Código si NO tiene permiso -->
@endcannot

{{-- If-else con permisos --}}
@can('update', $model)
    <button>Editar</button>
@else
    <span>Solo lectura</span>
@endcan

{{-- Verificar múltiples permisos (OR) --}}
@canany(['update', 'delete'], $model)
    <div class="admin-actions">...</div>
@endcanany

{{-- Verificar clase en lugar de instancia --}}
@can('create', App\Models\Vehicle::class)
    <a href="{{ route('vehicles.create') }}">Nuevo Vehículo</a>
@endcan
```

---

## Helpers de PHP

```php
// En controladores/componentes
auth()->user()->can('update', $vehicle);
auth()->user()->cannot('delete', $user);

// Gate facade
use Illuminate\Support\Facades\Gate;

Gate::allows('update', $vehicle);
Gate::denies('delete', $user);
Gate::authorize('view', $reservation); // Lanza 403 si no tiene permiso

// Verificar antes de hacer algo
if (auth()->user()->can('update', $vehicle)) {
    // Realizar acción
}

// Con ternario
$canEdit = auth()->user()->can('update', $vehicle) ? 'Sí' : 'No';
```

---

## Auto-Discovery

Laravel automáticamente descubrirá las policies si siguen la convención de nombres:
- Modelo: `Vehicle` → Policy: `VehiclePolicy`
- Modelo: `User` → Policy: `UserPolicy`

Si no sigues la convención, regístralas manualmente en `AppServiceProvider`:
```php
Gate::policy(Reservation::class, ReservationPolicy::class);
```

---

## Testing

```php
// En tests
public function test_only_admin_can_delete_users()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'cliente']);
    
    $this->assertTrue($admin->can('delete', $user));
    $this->assertFalse($user->can('delete', $admin));
}
```
