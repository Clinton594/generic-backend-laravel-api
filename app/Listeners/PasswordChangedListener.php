<?php

namespace App\Listeners;

use App\Cache\UserCache;
use App\Services\Notify\NotifyService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PasswordChangedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        $notifyChannels = object(config('data.notifyChannels'));

        // Notify the user
        NotifyService::Notify(
            UserCache::ByID($event->user->id),
            [
                "subject" => "Password Change",
                "body" =>  "Hello there, just to notify you that your password has been changed",
            ],
            [$notifyChannels->email => 'email.password-reset-success']
        );
    }
}
