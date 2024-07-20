<?php

namespace App\Observers;

use App\Cache\UserCache;
use App\Listeners\RegistrationListener;
use App\Models\User;
use App\Services\Notify\Logger;
use App\Services\Notify\LogService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        UserCache::remove($user);
        Event::dispatch(new Registered($user));
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Logger::updated($user);
        UserCache::remove($user);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        Logger::deleted($user);
        // Clear user Cache
        UserCache::remove($user);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        // Clear user Cache
        UserCache::remove($user);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        // Clear user Cache
        UserCache::remove($user);
    }
}
