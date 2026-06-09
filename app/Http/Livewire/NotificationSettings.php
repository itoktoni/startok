<?php

namespace App\Http\Livewire;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationSettings extends Component
{
    public bool $isSubscribed = false;

    public bool $notificationSupported = false;

    public string $permissionState = 'default';

    public function mount(): void
    {
        $this->isSubscribed = PushSubscription::where('user_id', Auth::id())->exists();
        $this->notificationSupported = true;
        $this->permissionState = 'default';
    }

    public function toggleSubscription(): void
    {
        if ($this->isSubscribed) {
            PushSubscription::where('user_id', Auth::id())->delete();
            $this->isSubscribed = false;
            session()->flash('status', 'Push notifications disabled.');
        } else {
            $this->isSubscribed = true;
            session()->flash('status', 'Push notifications enabled.');
        }
    }

    public function render()
    {
        return view('livewire.notification-settings')->layout('layouts.app', ['title' => 'Notification Settings']);
    }
}
