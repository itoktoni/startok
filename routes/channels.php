<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications', function ($user) {
    return true;
});

Broadcast::channel('order.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});