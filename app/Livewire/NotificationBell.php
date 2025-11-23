<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $showDropdown = false;
    public $notifications = [];
    public $unreadCount = 0;

    protected $listeners = ['notificationCreated' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Notification::forUser(auth()->id())
            ->latest()
            ->limit(5)
            ->get();
        
        $this->unreadCount = Notification::forUser(auth()->id())
            ->unread()
            ->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            $this->loadNotifications();
            
            $this->dispatch('notification-read');
        }
    }

    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->each(fn($n) => $n->markAsRead());
        
        $this->loadNotifications();
        
        $this->dispatch('toast', 
            type: 'success',
            message: 'Todas las notificaciones marcadas como leídas'
        );
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->delete();
            $this->loadNotifications();
            
            $this->dispatch('toast', 
                type: 'success',
                message: 'Notificación eliminada'
            );
        }
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
