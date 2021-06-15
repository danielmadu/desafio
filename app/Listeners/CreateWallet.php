<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class CreateWallet
{

    public function handle(Registered $event)
    {
        $user = User::find($event->user->id);
        $user->wallet()->create();
    }
}
