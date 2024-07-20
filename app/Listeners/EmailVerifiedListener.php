<?php

namespace App\Listeners;

use App\Cache\UserCache;
use App\Services\Notify\NotifyService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailVerifiedListener
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
    public function handle(Verified $event): void
    {
        $notifyChannels = object(config('data.notifyChannels'));

        // Notify the user
        NotifyService::Notify(
            UserCache::ByID($event->user->id, true),
            [
                "subject" => "EMAIL VERIFIED",
                "body" =>  "Next step is to find an asset to start staking and earning passively from",
            ],
            [$notifyChannels->email => 'email.verification-success']
        );
    }
}
