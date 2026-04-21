<?php

namespace HamodaDev\Moyasar;

use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Connector;

class Moyasar extends Connector
{
    public function __construct(
        protected readonly string $baseUrl,
        protected readonly string $apiKey,
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): BasicAuthenticator
    {
        return new BasicAuthenticator($this->apiKey, '');
    }
}
