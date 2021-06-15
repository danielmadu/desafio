<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Jobs\SendTransactionReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdatePayeeWallet
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
        $wallet = $transaction->to->wallet;
        $wallet->total_amount += $transaction->value;
        $wallet->save();
        SendTransactionReceivedNotification::dispatch($transaction);
    }
}
