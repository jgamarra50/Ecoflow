<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureReservationOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reservationId = $request->route('id') ?? $request->route('reservation');
        
        if (!$reservationId) {
            abort(404, 'Reservación no encontrada');
        }

        $reservation = Reservation::find($reservationId);

        if (!$reservation) {
            abort(404, 'Reservación no encontrada');
        }

        $user = auth()->user();

        // Admin and operators can access all reservations
        if (in_array($user->role, ['admin', 'operador'])) {
            return $next($request);
        }

        // Clients can only access their own reservations
        if ($user->role === 'cliente' && $reservation->user_id === $user->id) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a esta reservación');
    }
}
