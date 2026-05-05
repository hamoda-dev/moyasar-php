<?php

namesapce Tests\Config;

use Saloon\Http\Faking\MockClient;

class ExtraData
{
    public function __construct(
        private readonly MockClient $client
    ) {}

    public function getClient(): MockClient
    {
        return $this->client;
    }
}
