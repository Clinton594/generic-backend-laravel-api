<?php

namespace App\Listeners;

use App\Cache\UserCache;
use App\Models\Company;
use App\Models\User;
use App\Services\Notify\Logger;
use App\Services\Notify\NotifyService;
use App\Services\TokenManagementService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class RegistrationListener
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
    public function handle(Registered $event): void
    {
        $notifyChannels = object(config('data.notifyChannels'));
        $tokenFor = object(config('data.tokenFor'));

        $user = $event->user;
        $company = Company::get();

        // Make email confirmation token only for manual registration
        if (!$user->email_verified_at) {
            $otp = TokenManagementService::sendToken($user, $tokenFor->emailVerification);
        } else $otp = null;

        // Notify the user
        NotifyService::Notify($user, [
            "subject" => "Welcome to {$company->name}",
            "body" => "Your account registration was successful. We are happy to recieve you, {$user->first_name}",
            "otp" => $otp
        ], [$notifyChannels->email => 'email.welcome']);

        // Log Registration activity
        $user = UserCache::ByID($user->id, true, false);
        Logger::created($user);
    }
}
