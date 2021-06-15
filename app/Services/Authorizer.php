<?php

namespace App\Services;

use App\Services\Authorizer\Client;
use App\Services\Interfaces\ServiceInterface;
use GuzzleHttp\Exception\GuzzleException;

class Authorizer implements ServiceInterface
{
    private $client;

    private $message = '';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return bool
     * @throws GuzzleException
     */
    public function check(): bool
    {
        $response = $this->client->get();
        $this->message = $response->getMessage();
        if ($response->ok()) {
            if ($this->message === 'Autorizado') {
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
