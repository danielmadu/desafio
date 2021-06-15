<?php

namespace App\Providers;

use App\Events\TransactionCreated;
use App\Events\UserCreated;
use App\Listeners\CreateWallet;
use App\Listeners\UpdatePayeeWallet;
use App\Listeners\UpdatePayerWallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateWallet::class,
        ],
        TransactionCreated::class => [
            UpdatePayerWallet::class,
            UpdatePayeeWallet::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
