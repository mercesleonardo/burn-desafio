<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Notifications\WelcomeNotification;

class SendWelcomeNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $event->user->notify(new WelcomeNotification());
    }
}
