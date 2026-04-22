<?php

namespace HamodaDev\Moyasar;

use HamodaDev\Moyasar\Invoice\InvoiceResource;
use HamodaDev\Moyasar\Payment\PaymentResource;
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

    public function invoice(): InvoiceResource
    {
        return new InvoiceResource($this);
    }

    public function payment(): PaymentResource
    {
        return new PaymentResource($this);
    }
}
