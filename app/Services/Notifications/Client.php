<?php

namespace App\Services\Notifications;

use App\Services\Contracts\Service;
use App\Services\Interfaces\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Client implements ClientInterface
{
    use Service;

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('notifications.url'),
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function get(): Client
    {
        $this->response = $this->client->request('GET');
        return $this;
    }
}
