<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Notifications\Client;

class Notification implements ServiceInterface
{
    private $client;

    private $message = '';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function sendNotification($email = ''): bool
    {
        $response = $this->client->get();
        $this->message = $response->getMessage();
        if ($response->ok()) {
            if ($this->message === 'Success') {
                return true;
            }
            return false;
        }

        return false;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
