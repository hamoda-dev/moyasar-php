<?php

namespace HamodaDev\Moyasar\Payment\APIs;

use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RefundPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $paymentId,
        public readonly ?int $amount = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/payments/{$this->paymentId}/refund";
    }

    public function defaultBody(): array
    {
        return array_filter([
            'amount' => $this->amount,
        ], fn (mixed $value): bool => $value !== null);
    }

    public function createDtoFromResponse(Response $response): PaymentDTO
    {
        return PaymentDTO::fromResponse($response);
    }
}
