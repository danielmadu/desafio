<?php

namespace App\Services\Authorizer;

use App\Services\Contracts\Service;
use App\Services\Interfaces\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * @property ResponseInterface $response
 */
class Client implements ClientInterface
{
    use Service;

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('authorizer.url'),
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function get(): Client
    {
        $this->response = $this->client->request('GET', 'v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        return $this;
    }
}
