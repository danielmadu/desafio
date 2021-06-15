<?php

namespace App\Services\Contracts;

trait Service
{
    private $client;

    private $response;

    public function getContent()
    {
        return json_decode($this->response->getBody()->getContents());
    }

    public function ok(): bool
    {
        if ($this->response->getStatusCode() === 200) {
            return true;
        }

        return false;
    }

    public function getMessage()
    {
        if(isset(($this->getContent())->message)) {
            return ($this->getContent())->message;
        }

        return null;
    }
}
