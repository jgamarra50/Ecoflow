<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <!-- Bell Icon with Badge -->
    <button @click="open = !open" type="button"
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if($unreadCount > 0)
            <span
                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
        style="display: none;">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Notificaciones</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Marcar todas como leídas
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div
                    class="px-4 py-3 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'opacity-60' : '' }} border-b border-gray-100 last:border-0">
                    <div class="flex items-start">
                        @if($notification->icon)
                            <div class="flex-shrink-0 text-2xl mr-3">{{ $notification->icon }}</div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <h4 class="text-sm font-semibold text-gray-900">{{ $notification->title }}</h4>
                                @if(!$notification->is_read)
                                    <span class="ml-2 flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full"></span>
                                @endif
                            </div>

                            <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>

                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $notification->created_at->diffForHumans() }}</span>

                                <div class="flex gap-2">
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" @click="open = false"
                                            wire:click="markAsRead({{ $notification->id }})"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $notification->action_text ?? 'Ver' }}
                                        </a>
                                    @endif

                                    @if(!$notification->is_read)
                                        <button wire:click="markAsRead({{ $notification->id }})"
                                            class="text-green-600 hover:text-green-800 font-medium">
                                            Marcar leída
                                        </button>
                                    @endif

                                    <button wire:click="deleteNotification({{ $notification->id }})"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <div class="text-4xl mb-2">🔔</div>
                    <p class="text-gray-500">No tienes notificaciones</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if(count($notifications) > 0)
            <div class="px-4 py-3 border-t border-gray-200 text-center">
                <a href="{{ route('notifications.index') }}" @click="open = false"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Ver todas las notificaciones
                </a>
            </div>
        @endif
    </div>
</div>