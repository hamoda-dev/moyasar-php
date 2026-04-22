<?php

namespace HamodaDev\Moyasar\Payment\APIs;

use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class VoidPaymentRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $paymentId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/payments/{$this->paymentId}/void";
    }

    public function createDtoFromResponse(Response $response): PaymentDTO
    {
        return PaymentDTO::fromResponse($response);
    }
}
