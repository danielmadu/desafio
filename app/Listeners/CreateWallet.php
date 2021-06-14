<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateWallet
{

    public function handle(Registered $event)
    {
        $user = User::find($event->user->id);
        $user->wallet()->create();
    }
}
