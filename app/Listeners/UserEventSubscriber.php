<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use App\Events\LogoutEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;

class UserEventSubscriber
{
    public function handleUserLogin(Login $event): void
    {
        $user = $event->user;
        // Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(5));
        broadcast(new LoginEvent($user)); //Chỉ định rằng sự kiện này chỉ được phát tới các client khác, không phát tới chính người dùng hiện tại.
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void
    {
        $user = $event->user;
        // Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(5));
        broadcast(new LogoutEvent($user)); //Chỉ định rằng sự kiện này chỉ được phát tới các client khác, không phát tới chính người dùng hiện tại.
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
        ];
    }
}