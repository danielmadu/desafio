<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePayerWallet
{
    /**
     * Handle the event.
     *
     * @param  TransactionCreated  $event
     * @return void
     */
    public function handle(TransactionCreated $event)
    {
        $transaction = $event->transaction;
        $wallet = $transaction->from->wallet;
        $wallet->total_amount -= $transaction->value;
        $wallet->save();
    }
}
