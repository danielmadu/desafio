<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\Notification;
use App\Services\Notifications\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTransactionReceivedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;

    protected $triesCount;

    /**
     * Create a new job instance.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction, $tryNum = 0)
    {
        $this->transaction = $transaction;
        $this->triesCount = $tryNum;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->triesCount < 4){
            $client = new Client();
            $notificationService = new Notification($client);
            if(!$notificationService->sendNotification($this->transaction->payee->email)) {
                SendTransactionReceivedNotification::dispatch($this->transaction, $this->triesCount++);
            }
        }

    }
}
