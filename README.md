# 🌿 Ecoflow - Sistema de Gestión de Vehículos Eléctricos

Sistema completo de gestión y reserva de vehículos eléctricos (scooters, bicicletas y skateboards) desarrollado con Laravel 11, Livewire 3 y Tailwind CSS.

---

## 📋 Tabla de Contenidos

1. [Características Principales](#características-principales)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Instalación](#instalación)
4. [Configuración Inicial](#configuración-inicial)
5. [Arquitectura del Proyecto](#arquitectura-del-proyecto)
6. [Roles y Permisos](#roles-y-permisos)
7. [Funcionalidades por Rol](#funcionalidades-por-rol)
8. [Flujos de Navegación](#flujos-de-navegación)
9. [Módulos Principales](#módulos-principales)
10. [Testing de Funcionalidades](#testing-de-funcionalidades)
11. [API Endpoints](#api-endpoints)
12. [Optimizaciones](#optimizaciones)

---

## 🎯 Características Principales

- ✅ **Sistema Multi-Rol:** Clientes, Operadores, Técnicos y Administradores
- ✅ **Gestión de Reservas:** Wizard de 4 pasos con validación de disponibilidad
- ✅ **Módulo de Operadores:** Gestión de entregas, retornos y reporte de problemas
- ✅ **Módulo de Técnicos:** Gestión de mantenimientos asignados
- ✅ **Telemetría en Tiempo Real:** Simulación de datos de vehículos (batería, ubicación, distancia)
- ✅ **Mapas Interactivos:** Leaflet.js para visualización de estaciones y vehículos
- ✅ **Notificaciones:** Sistema de notificaciones en BD con badges y dropdown
- ✅ **Tracking de Reservas:** Seguimiento en tiempo real con simulación de movimiento
- ✅ **Authorization Policies:** Control granular de permisos por recurso
- ✅ **Validaciones Robustas:** Form Requests con prevención de doble reserva

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework:** Laravel 11
- **Lenguaje:** PHP 8.2+
- **Base de Datos:** MySQL/MariaDB
- **ORM:** Eloquent

### Frontend
- **UI Framework:** Livewire 3
- **CSS:** Tailwind CSS 3
- **JavaScript:** Alpine.js
- **Mapas:** Leaflet.js 1.9.4
- **Alertas:** SweetAlert2
- **Gráficos:** Chart.js 4.4.0
- **Build Tool:** Vite

### Autenticación
- Laravel Breeze (Livewire + Blade)

---

## 📦 Instalación

### Requisitos Previos
- PHP 8.2 o superior
- Composer
- Node.js 18+ y NPM
- MySQL 8.0+

### Pasos de Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/ecoflow.git
cd ecoflow

# 2. Instalar dependencias de PHP
composer install

# 3. Instalar dependencias de Node
npm install

# 4. Copiar el archivo de entorno
cp .env.example .env

# 5. Generar la clave de aplicación
php artisan key:generate

# 6. Configurar la base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecoflow
DB_USERNAME=root
DB_PASSWORD=

# 7. Ejecutar migraciones y seeders
php artisan migrate --seed

# 8. Crear enlace simbólico para almacenamiento
php artisan storage:link

# 9. Compilar assets
npm run dev

# 10. Iniciar el servidor
php artisan serve
```

El proyecto estará disponible en `http://127.0.0.1:8000`

---

## ⚙️ Configuración Inicial

### Usuarios de Prueba

Después de ejecutar los seeders, tendrás los siguientes usuarios:

| Rol | Email | Password | Descripción |
|-----|-------|----------|-------------|
| Cliente | `cliente@ecoflow.com` | `password` | Usuario que puede hacer reservas |
| Operador | `operador@ecoflow.com` | `password` | Gestiona entregas y retornos |
| Técnico | `tecnico@ecoflow.com` | `password` | Realiza mantenimientos |
| Admin | `admin@ecoflow.com` | `password` | Acceso completo al sistema |

### Datos Iniciales

Los seeders crean:
- 3 Estaciones de vehículos
- 15 Vehículos (5 scooters, 5 bicicletas, 5 skateboards)
- Datos de telemetría para cada vehículo
- Reservas de ejemplo
- Tickets de mantenimiento

---

## 🏗️ Arquitectura del Proyecto

### Estructura de Directorios

```
ecoflow/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── TelemetryController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureUserHasRole.php
│   │   │   └── EnsureReservationOwnership.php
│   │   └── Requests/
│   │       ├── StoreReservationRequest.php
│   │       └── StoreMaintenanceRequest.php
│   ├── Livewire/
│   │   ├── AdminDashboard.php
│   │   ├── AdminTelemetry.php
│   │   ├── ClientDashboard.php
│   │   ├── NewReservation.php (Wizard 4 pasos)
│   │   ├── MyReservations.php
│   │   ├── OperatorDeliveries.php
│   │   ├── OperatorReports.php
│   │   ├── PublicMap.php
│   │   ├── ReservationTracking.php
│   │   ├── NotificationBell.php
│   │   └── VehicleMap.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Vehicle.php
│   │   ├── Station.php
│   │   ├── Reservation.php
│   │   ├── Maintenance.php
│   │   ├── Telemetry.php
│   │   └── Notification.php
│   ├── Policies/
│   │   ├── VehiclePolicy.php
│   │   ├── ReservationPolicy.php
│   │   ├── MaintenancePolicy.php
│   │   └── UserPolicy.php
│   ├── Jobs/
│   │   └── ProcessTelemetryUpdate.php
│   └── Console/
│       └── Commands/
│           └── UpdateTelemetry.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── livewire/
│       ├── components/
│       ├── layouts/
│       └── reservations/
└── routes/
    ├── web.php
    ├── api.php
    └── console.php
```

### Patrones de Diseño

- **Repository Pattern:** Eloquent ORM
- **Policy Pattern:** Authorization granular
- **Observer Pattern:** Livewire events
- **Command Pattern:** Artisan commands para telemetría
- **Job Pattern:** Queue jobs para procesamiento

---

## 👥 Roles y Permisos

### Cliente (`cliente`)
**Permisos:**
- ✅ Ver mapa público de vehículos
- ✅ Crear reservas (wizard)
- ✅ Ver sus propias reservas
- ✅ Cancelar sus reservas (si no están completadas)
- ✅ Trackear sus reservas en tiempo real
- ✅ Ver su dashboard personalizado

**Restricciones:**
- ❌ No puede ver reservas de otros usuarios
- ❌ No puede modificar vehículos
- ❌ No puede acceder a módulos administrativos

### Operador (`operador`)
**Permisos:**
- ✅ Gestionar entregas de vehículos
- ✅ Procesar retornos de vehículos
- ✅ Reportar problemas de vehículos
- ✅ Ver todas las reservas
- ✅ Cambiar estado de vehículos
- ✅ Crear tickets de mantenimiento

**Restricciones:**
- ❌ No puede eliminar vehículos
- ❌ No puede gestionar usuarios

### Técnico (`tecnico`)
**Permisos:**
- ✅ Ver tickets asignados a él
- ✅ Actualizar tickets asignados
- ✅ Completar mantenimientos asignados
- ✅ Actualizar información de vehículos
- ✅ Realizar mantenimiento preventivo

**Restricciones:**
- ❌ No puede ver tickets de otros técnicos
- ❌ No puede asignar tickets
- ❌ No puede eliminar vehículos

### Administrador (`admin`)
**Permisos:**
- ✅ Acceso completo a todo el sistema
- ✅ Gestionar usuarios (crear, editar, eliminar, cambiar roles)
- ✅ Gestionar vehículos (CRUD completo)
- ✅ Ver telemetría en tiempo real
- ✅ Asignar tickets de mantenimiento
- ✅ Ver todas las reservas y modificarlas
- ✅ Acceso a reportes y estadísticas

---

## 🎨 Funcionalidades por Rol

### 🛒 CLIENTE

#### 1. Dashboard del Cliente
**Ruta:** `/dashboard` (con role:cliente)

**Componente:** `ClientDashboard.php`

**Funcionalidades:**
- 📊 Resumen de reservas activas
- 🚲 Mapa interactivo de estaciones cercanas
- 📅 Próximas reservas
- ✨ Botón CTA "Nueva Reserva"

**Flujo:**
```
Login → Dashboard → 
  → Ver mapa de estaciones (Leaflet.js)
  → Click "Nueva Reserva" → /reservations/new
```

#### 2. Nueva Reserva (Wizard)
**Ruta:** `/reservations/new`

**Componente:** `NewReservation.php`

**Pasos del Wizard:**

**PASO 1 - Selección de Vehículo**
- Vista en grid de vehículos disponibles
- Filtros por tipo (todos, scooter, bicicleta, skateboard)
- Muestra: batería, ubicación, placa
- Click en tarjeta selecciona el vehículo
- `wire:click="selectVehicle($vehicleId)"`

**PASO 2 - Fechas y Entrega**
- Selección de fecha/hora inicio y fin
- Método de entrega:
  - **Pickup:** Seleccionar estación
  - **Delivery:** Ingresar dirección
- Validación de disponibilidad en tiempo real
- `checkAvailability()` verifica conflictos

**PASO 3 - Método de Retorno**
- Método de retorno:
  - **Return:** Devolver en estación
  - **Pickup:** Recoger en dirección
- Cálculo de precio dinámico
- Fees adicionales por delivery/pickup

**PASO 4 - Confirmación**
- Resumen completo de la reserva
- Vehículo seleccionado
- Fechas y horarios
- Precio total desglosado
- Botón "Confirmar Reserva"

**Validaciones:**
- ✅ Vehículo debe estar disponible
- ✅ Fechas no pueden tener conflictos
- ✅ Máximo 30 días por reserva
- ✅ Fecha inicio debe ser futura
- ✅ Prevención de doble reserva (StoreReservationRequest)

**Flujo:**
```
/reservations/new →
  Paso 1: Seleccionar vehículo →
  Paso 2: Fechas + método entrega →
  Paso 3: Método retorno →
  Paso 4: Confirmar →
  SweetAlert éxito →
  Redirect a /reservations
```

#### 3. Mis Reservas
**Ruta:** `/reservations`

**Componente:** `MyReservations.php`

**Funcionalidades:**
- 📋 Lista de todas las reservas del usuario
- 🏷️ Badges de estado (pending, confirmed, active, completed, cancelled)
- 🗺️ Botón "Track" para seguimiento en tiempo real
- ❌ Botón "Cancelar" (si aplica)
- 🧾 Botón "Ver Recibo"

**Estados de Reserva:**
- **Pending:** Esperando confirmación
- **Confirmed:** Confirmada por operador
- **Active:** En uso actualmente
- **Completed:** Finalizada
- **Cancelled:** Cancelada

**Acciones Disponibles:**
```blade
@can('cancel', $reservation)
  <button wire:click="cancelReservation({{ $reservation->id }})">
    Cancelar
  </button>
@endcan
```

**Flujo:**
```
/reservations →
  Ver lista →
  Click "Track" → /reservations/{id}/track
  Click "Cancelar" → Confirmar con SweetAlert → Actualiza estado
  Click "Ver Recibo" → /reservations/{id}/receipt
```

#### 4. Tracking de Reserva
**Ruta:** `/reservations/{id}/track`

**Componente:** `ReservationTracking.php`

**Middleware:** `reservation.owner` (solo dueño puede ver)

**Funcionalidades:**
- 🗺️ Mapa en tiempo real con Leaflet
- 📍 Ubicación actual del vehículo (simulada)
- 🚩 Marcador de estación de origen
- ⏱️ Tiempo estimado restante
- 📏 Distancia recorrida
- 🔋 Nivel de batería actual
- 📞 Botón "Contactar Soporte"

**Actualización Automática:**
- Polling cada 5 segundos
- Movimiento simulado del vehículo
- Actualización de métricas en sidebar

**Flujo:**
```
/reservations/{id}/track →
  Verificación de ownership →
  Renderiza mapa con ubicación →
  Auto-refresh cada 5s →
  Movimiento simulado del vehículo
```

---

### 🚚 OPERADOR

#### 1. Dashboard del Operador
**Ruta:** `/operator/dashboard`

**Componente:** `OperatorDashboard.php`

**Funcionalidades:**
- 📦 Entregas pendientes hoy
- 🔄 Retornos pendientes
- ⚠️ Problemas reportados
- 📊 Estadísticas del día

#### 2. Gestión de Entregas
**Ruta:** `/operator/deliveries`

**Componente:** `OperatorDeliveries.php`

**Tabs:**
1. **Entregas Pendientes**
2. **Retornos Pendientes**

**Funcionalidad de Entrega:**
- Ver detalles de reserva
- Confirmar identidad del cliente
- Confirmar estado del vehículo (batería, daños)
- Subir foto del vehículo
- Marcar como entregado

**Formulario de Entrega:**
```blade
<form wire:submit.prevent="confirmDelivery">
  <input wire:model="identityConfirmed" type="checkbox">
  <input wire:model="vehicleInspected" type="checkbox">
  <input wire:model="deliveryPhoto" type="file">
  <textarea wire:model="notes"></textarea>
  <button type="submit">Confirmar Entrega</button>
</form>
```

**Funcionalidad de Retorno:**
- Verificar condición del vehículo
- Registrar kilometraje final
- Registrar nivel de batería
- Reportar daños si existen
- Subir fotos
- Completar reserva

**Flujo:**
```
/operator/deliveries →
  Tab "Entregas" →
  Click en reserva →
  Modal con formulario →
  Confirmar entrega →
  SweetAlert éxito →
  Actualiza estado a "active"

  Tab "Retornos" →
  Click en reserva →
  Modal con formulario →
  Inspeccionar vehículo →
  Completar retorno →
  Estado a "completed"
```

#### 3. Reporte de Problemas
**Ruta:** `/operator/reports`

**Componente:** `OperatorReports.php`

**Formulario:**
- 🚲 Seleccionar vehículo (dropdown)
- 📝 Categoría del problema (mecánico, eléctrico, batería, otros)
- ✍️ Descripción detallada
- 📸 Subir foto (opcional)
- ⚡ Nivel de prioridad (low, medium, high, urgent)

**Acción:**
- Crea automáticamente un ticket de mantenimiento
- Cambia estado del vehículo a "maintenance"
- Notifica a técnicos disponibles

**Flujo:**
```
/operator/reports →
  Seleccionar vehículo →
  Elegir categoría →
  Escribir descripción →
  Subir foto →
  Seleccionar prioridad →
  Submit →
  Crea Maintenance ticket →
  Vehículo → status: "maintenance"
```

---

### 🔧 TÉCNICO

#### 1. Dashboard del Técnico
**Ruta:** `/technician/dashboard`

**Componente:** `TechnicianDashboard.php`

**Funcionalidades:**
- 🎫 Tickets asignados a mí
- ⏳ Tickets en progreso
- ✅ Completados hoy
- 🚲 Vehículos en mantenimiento

#### 2. Gestión de Tickets
**Ruta:** `/technician/vehicles`

**Componente:** `TechnicianVehicles.php`

**Funcionalidades:**
- Ver solo tickets asignados al técnico
- Actualizar estado (pending → in_progress → completed)
- Agregar notas de trabajo
- Registrar costos
- Marcar como completado

**Policy de Autorización:**
```php
// Solo puede actualizar tickets asignados a él
public function update(User $user, Maintenance $maintenance): bool
{
    return $maintenance->technician_id === $user->id;
}
```

**Flujo:**
```
/technician/vehicles →
  Ver mis tickets →
  Click en ticket →
  Modal con detalles →
  Actualizar estado →
  Agregar notas →
  Registrar costo →
  Marcar completado →
  Vehículo regresa a "available"
```

---

### 👨‍💼 ADMINISTRADOR

#### 1. Dashboard Admin
**Ruta:** `/admin/dashboard`

**Componente:** `AdminDashboard.php`

**Funcionalidades:**
- 📊 Dashboard con métricas generales
- 💰 Ingresos del mes
- 🚲 Vehículos activos
- 👥 Usuarios registrados
- 📈 Gráficos de tendencias

#### 2. Gestión de Vehículos
**Ruta:** `/admin/vehicles`

**Componente:** `VehiclesManager.php`

**Funcionalidades:**
- ➕ Crear nuevo vehículo
- ✏️ Editar información
- 🗑️ Eliminar vehículo
- 🔄 Cambiar estado
- 🔋 Ver telemetría

**Policy:**
```php
@can('create', App\Models\Vehicle::class)
  <button>Nuevo Vehículo</button>
@endcan

@can('update', $vehicle)
  <button>Editar</button>
@endcan
```

#### 3. Gestión de Usuarios
**Ruta:** `/admin/users`

**Componente:** `UsersManager.php`

**Funcionalidades:**
- ➕ Crear usuario
- ✏️ Editar perfil
- 🔄 Cambiar rol
- 🚫 Suspender usuario
- 🗑️ Eliminar (excepto a sí mismo)

**Protección:**
```php
// No puede cambiar su propio rol ni eliminarse
public function changeRole(User $user, User $model): bool
{
    return $user->role === 'admin' && $user->id !== $model->id;
}
```

#### 4. Gestión de Mantenimientos
**Ruta:** `/admin/maintenances`

**Componente:** `MaintenancesManager.php`

**Funcionalidades:**
- Ver todos los tickets
- Asignar técnico a ticket
- Cambiar prioridad
- Ver historial completo

#### 5. Telemetría en Tiempo Real
**Ruta:** `/admin/telemetry`

**Componente:** `AdminTelemetry.php`

**Funcionalidades:**
- 📊 Grid de todos los vehículos
- 🔋 Indicadores de batería con colores:
  - 🟢 Verde: >60%
  - 🟡 Amarillo: 30-60%
  - 🔴 Rojo: <30%
- ⚠️ Alertas automáticas de batería baja
- 📈 Gráfico Chart.js de historial de batería
- 🔄 Auto-refresh cada 10 segundos (Livewire polling)
- 📍 Coordenadas GPS en tiempo real
- 📏 Kilometraje total

**Interacción:**
```
Grid de vehículos →
  Click "Ver Historial" →
  Renderiza gráfico Chart.js →
  Muestra últimas 20 lecturas →
  Auto-actualización cada 10s
```

---

## 🗺️ Funcionalidades Públicas

### Mapa Público de Vehículos
**Ruta:** `/map` (sin autenticación)

**Componente:** `PublicMap.php`

**Rate Limiting:** 60 requests por minuto

**Funcionalidades:**
- 🗺️ Mapa Leaflet con todas las estaciones
- 🚲 Marcadores de vehículos disponibles
- 🔍 Filtros por tipo de vehículo
- 📊 Estadísticas en sidebar:
  - Total disponibles
  - Por tipo (scooter, bicycle, skateboard)
- 🔄 Toggle para mostrar/ocultar estaciones
- ⏰ Auto-refresh cada 30 segundos
- 💚 Popups con información de vehículo:
  - Tipo, marca, modelo
  - Nivel de batería
  - Botón "Reserve Now" (lleva a registro)

**Flujo:**
```
/map →
  Ver mapa público →
  Filtrar por tipo →
  Click en vehículo →
  Popup con info →
  Click "Reserve Now" →
  Redirect a /register si no autenticado
  Redirect a /reservations/new si autenticado
```

---

## 📱 Módulos Principales

### 1. Sistema de Notificaciones

**Componente:** `NotificationBell.php`

**Ubicación:** En todos los layouts autenticados (header)

**Funcionalidades:**
- 🔔 Icono de campana con badge de contador
- 📬 Dropdown con últimas 5 notificaciones
- ✅ Marcar como leída
- ✅ Marcar todas como leídas
- 🗑️ Eliminar notificación
- 🔗 Links de acción personalizados

**Tipos de Notificaciones:**
- Nueva reserva confirmada
- Mantenimiento completado
- Batería baja en vehículo
- Ticket asignado (técnicos)
- Retorno pendiente (operadores)

**Uso Programático:**
```php
Notification::createForUser(
    $userId, 
    'success', 
    '¡Nueva reserva!', 
    'Tu reserva #123 ha sido confirmada',
    [
        'icon' => '🎉',
        'action_url' => route('reservations.show', 123),
        'action_text' => 'Ver reserva'
    ]
);
```

**Toasts (Acciones en Segundo Plano):**
```php
$this->dispatch('toast', 
    type: 'success',
    message: 'Operación completada'
);
```

### 2. Sistema de Telemetría

**Comando Artisan:** `php artisan telemetry:update`

**Job:** `ProcessTelemetryUpdate.php`

**Scheduler:** Ejecuta cada minuto automáticamente

**Simulación de Datos:**
- 🔋 Batería: ±5% (decrece si está en uso)
- 📍 Ubicación: ±0.001° (~100m) si está reservado
- 📏 Distancia: +0.3-0.7 km por actualización

**API Endpoints:**
```
GET /api/vehicles/telemetry - Todos los vehículos
GET /api/vehicles/{id}/telemetry - Vehículo específico
GET /api/vehicles/{id}/telemetry/history?limit=20 - Historial
```

**Cómo Probar:**
```bash
# Manual
php artisan telemetry:update

# Solo vehículos activos
php artisan telemetry:update --active-only

# Vehículo específico
php artisan telemetry:update --vehicle=1

# Scheduler en desarrollo
php artisan schedule:work
```

### 3. Wizard de Reservas

**Componente:** `NewReservation.php`

**Estado del Wizard:**
```php
public $currentStep = 1; // 1-4
public $selectedVehicleId;
public $startDate;
public $endDate;
public $deliveryMethod; // 'pickup' | 'delivery'
public $returnMethod; // 'return' | 'pickup'
```

**Navegación:**
```php
nextStep() // Valida y avanza
previousStep() // Retrocede sin validar
validateStep() // Validaciones específicas por paso
```

**Cálculo de Precio:**
```php
$pricePerDay = 50000; // COP
$basePrice = $days * $pricePerDay;
$deliveryFee = $deliveryMethod === 'delivery' ? 10000 : 0;
$pickupFee = $returnMethod === 'pickup' ? 10000 : 0;
$totalPrice = $basePrice + $deliveryFee + $pickupFee;
```

---

## 🧪 Testing de Funcionalidades

### Cómo Probar el Sistema Completo

#### 1. **Probar como Cliente**

```bash
# 1. Acceder al sistema
URL: http://127.0.0.1:8000
Email: cliente@ecoflow.com
Password: password

# 2. Dashboard
- Verifica que veas el mapa con estaciones
- Verifica el resumen de reservas

# 3. Crear Nueva Reserva
Click "Nueva Reserva" → /reservations/new
- Paso 1: Selecciona un scooter (filtra por tipo)
- Paso 2: Selecciona fechas (mañana + 2 días)
- Método: Pickup en una estación
- Paso 3: Return en la misma estación
- Paso 4: Verifica precio ($100,000 por 2 días)
- Confirma reserva

# 4. Ver Mis Reservas
/reservations → Deberías ver tu nueva reserva

# 5. Tracking
Click "Track" en tu reserva
- Verifica que se muestre el mapa
- Verifica actualización automática cada 5s
- Verifica métricas en sidebar

# 6. Cancelar Reserva
Vuelve a /reservations
Click "Cancelar"
Confirma en SweetAlert
```

#### 2. **Probar como Operador**

```bash
# Login
Email: operador@ecoflow.com
Password: password

# Dashboard Operador
/operator/dashboard
- Verifica entregas pendientes

# Gestionar Entregas
/operator/deliveries
Tab "Entregas Pendientes"
- Selecciona una reserva
- Marca checkboxes de confirmación
- Sube una foto
- Confirma entrega
- Verifica que pasa a "Active"

# Reporte de Problema
/operator/reports
- Selecciona un vehículo
- Categoría: "Batería"
- Descripción: "Batería no carga correctamente"
- Prioridad: High
- Sube foto
- Submit
- Verifica creación de ticket
```

#### 3. **Probar como Técnico**

```bash
# Login
Email: tecnico@ecoflow.com
Password: password

# Ver Tickets Asignados
/technician/vehicles
- Verifica que veas solo tus tickets
- Click en un ticket
- Cambiar estado a "In Progress"
- Agregar notas: "Revisando batería"
- Registrar costo: 50000
- Marcar como completado
- Verifica que vehículo regrese a disponible
```

#### 4. **Probar como Admin**

```bash
# Login
Email: admin@ecoflow.com
Password: password

# Gestión de Vehículos
/admin/vehicles
- Click "Nuevo Vehículo"
- Rellena formulario
- Guarda
- Edita un vehículo existente
- Elimina un vehículo de prueba

# Gestión de Usuarios
/admin/users
- Click "Nuevo Usuario"
- Crea un técnico nuevo
- Cambia el rol de un usuario
- NO puedes cambiar tu propio rol

# Telemetría
/admin/telemetry
- Verifica grid de vehículos
- Observa alertas de batería baja (<30%)
- Click "Ver Historial" en un vehículo
- Verifica gráfico Chart.js
- Espera 10 segundos, verifica auto-refresh

# Asignar Mantenimiento
/admin/maintenances
- Busca ticket sin asignar
- Asigna a un técnico
- Cambia prioridad
```

#### 5. **Probar Mapa Público**

```bash
# Sin autenticación
URL: /map

- Verifica que veas todas las estaciones
- Filtra por "Scooters"
- Verifica contador en sidebar
- Click en un vehículo
- Verifica popup con información
- Toggle "Show Stations"
- Espera 30s, verifica auto-refresh
- Click "Reserve Now" → Redirect a /register
```

#### 6. **Probar Notificaciones**

```bash
# Como cualquier usuario autenticado
- Verifica icono de campana en header
- Badge con número de no leídas
- Click en campana → Dropdown
- Verifica últimas notificaciones
- Click en "Marcar como leída"
- Click en "Marcar todas"
- Click en link de acción
- Elimina una notificación
```

#### 7. **Probar API de Telemetría**

```bash
# Postman o curl

# Todos los vehículos
GET http://127.0.0.1:8000/api/vehicles/telemetry

# Vehículo específico
GET http://127.0.0.1:8000/api/vehicles/1/telemetry

# Historial (últimas 20 lecturas)
GET http://127.0.0.1:8000/api/vehicles/1/telemetry/history?limit=20
```

---

## 🔒 Seguridad y Validaciones

### 1. **Form Requests**

**StoreReservationRequest:**
- Validación de disponibilidad del vehículo
- Prevención de doble reserva
- Duración máxima: 30 días
- Vehículo debe pertenecer a la estación seleccionada

**StoreMaintenanceRequest:**
- Solo usuarios autorizados (operador, admin, técnico)
- Sanitización de inputs con `strip_tags()`
- Validación de imágenes (max 2MB)

### 2. **Policies de Autorización**

Todas las acciones críticas están protegidas por policies:

```php
// En controladores/Livewire
$this->authorize('update', $vehicle);
$this->authorize('cancel', $reservation);
$this->authorize('changeRole', $user);
```

```blade
<!-- En vistas -->
@can('update', $vehicle)
  <button>Editar</button>
@endcan

@cannot('delete', $user)
  <span class="text-gray-400">No permitido</span>
@endcannot
```

### 3. **Middleware**

- **`role:admin`** - Solo administradores
- **`role:cliente`** - Solo clientes
- **`reservation.owner`** - Solo dueño de reserva

### 4. **Rate Limiting**

Rutas públicas limitadas a 60 requests/minuto:
```php
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/map', PublicMap::class);
});
```

### 5. **CSRF Protection**

Todos los formularios Blade incluyen `@csrf` automáticamente.

---

## 📊 Base de Datos

### Tablas Principales

1. **users** - Usuarios del sistema
2. **stations** - Estaciones de vehículos
3. **vehicles** - Vehículos eléctricos
4. **reservations** - Reservas de clientes
5. **maintenances** - Tickets de mantenimiento
6. **telemetries** - Datos de telemetría
7. **notifications** - Notificaciones de usuario
8. **vehicle_reports** - Reportes de operadores

### Relaciones

```
User
├── hasMany → Reservations
├── hasMany → Maintenances (as technician)
└── hasMany → Notifications

Vehicle
├── belongsTo → Station
├── hasMany → Reservations
├── hasMany → Telemetries
└── hasMany → Maintenances

Reservation
├── belongsTo → User
├── belongsTo → Vehicle
└── belongsTo → Station

Maintenance
├── belongsTo → Vehicle
├── belongsTo → User (technician)
└── belongsTo → User (created_by)
```

### Índices para Optimización

```sql
-- Reservations
INDEX (status)
INDEX (start_date, end_date)
INDEX (created_at)

-- Vehicles
INDEX (status)
INDEX (type)
INDEX (station_id, status)

-- Maintenances
INDEX (status)
INDEX (priority)
INDEX (technician_id)
INDEX (status, priority)

-- Telemetries
INDEX (vehicle_id, created_at)
```

---

## ⚡ Optimizaciones

### Cache de Laravel

```bash
# Cachear configuración (producción)
php artisan config:cache

# Cachear rutas (producción)
php artisan route:cache

# Cachear vistas
php artisan view:cache

# Limpiar todo (desarrollo)
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Eager Loading (N+1 Prevention)

```php
// ✅ Correcto
$vehicles = Vehicle::with(['station', 'latestTelemetry'])->get();

// ❌ Incorrecto (N+1)
$vehicles = Vehicle::all();
foreach ($vehicles as $vehicle) {
    echo $vehicle->station->name; // Query por cada vehículo
}
```

### Vite Build para Producción

```bash
# Construir assets optimizados
npm run build

# Características:
# - Minificación con Terser
# - Eliminación de console.log
# - Code splitting
# - Compresión ~30-40%
```

---

## 📚 Comandos Útiles

```bash
# Desarrollo
php artisan serve
npm run dev

# Migraciones
php artisan migrate:fresh --seed
php artisan migrate:rollback
php artisan migrate:status

# Telemetría
php artisan telemetry:update
php artisan telemetry:update --active-only
php artisan schedule:work

# Optimización
php artisan optimize
php artisan optimize:clear

# Livewire
php artisan livewire:make ComponentName
php artisan livewire:publish --config

# Políticas
php artisan make:policy VehiclePolicy --model=Vehicle

# Tests
php artisan test
php artisan test --filter ReservationTest
```

---

## 🐛 Troubleshooting

### Problema: Vehículos no se seleccionan en reserva
**Solución:** Ya corregido con `pointer-events-none` en elementos hijo

### Problema: Mapa no se muestra
**Solución:** Verificar que Leaflet CSS/JS estén cargados en layout

### Problema: "Undefined method 'latestTelemetry'"
**Solución:** Ejecutar migraciones y seeders

### Problema: Notificaciones no aparecen
**Solución:** Verificar que `@livewire('notification-bell')` esté en navigation

### Problema: CSRF token mismatch
**Solución:** Limpiar cache con `php artisan config:clear`

---

## 🎯 Próximas Mejoras

- [ ] Integración con pasarelas de pago (Stripe/PayU)
- [ ] Chat en tiempo real (Operador ↔ Cliente)
- [ ] Push notifications (Firebase)
- [ ] App móvil (React Native)
- [ ] Dashboard de analíticas con más gráficos
- [ ] Sistema de cupones y descuentos
- [ ] Integración con GPS real de vehículos IoT
- [ ] Reportes PDF descargables
- [ ] Multi-tenancy para múltiples ciudades

---

## 👨‍💻 Desarrolladores

**Juan Pablo Gamarra**
- GitHub: [@jgamarra50](https://github.com/jgamarra50)
- Email: jgamarra50@ecoflow.com

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT.

---

## 🙏 Agradecimientos

- Laravel Framework
- Livewire
- Tailwind CSS
- Leaflet.js
- Alpine.js
- Chart.js
- SweetAlert2

---

**¿Necesitas ayuda?** Abre un issue en GitHub o contacta al equipo de desarrollo.

**Última actualización:** Noviembre 2025
