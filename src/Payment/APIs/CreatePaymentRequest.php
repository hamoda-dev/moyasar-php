<?php

namespace HamodaDev\Moyasar\Payment\APIs;

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\Source\CreditCardSourceDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreatePaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly CreatePaymentDTO $createPaymentDTO,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/payments';
    }

    public function defaultBody(): array
    {
        $dto = $this->createPaymentDTO;
        $source = $dto->source instanceof CreditCardSourceDTO
            ? $dto->source->toArray()
            : $dto->source;

        return array_filter([
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'description' => $dto->description,
            'source' => $source,
            'given_id' => $dto->givenId,
            'callback_url' => $dto->callbackUrl,
            'metadata' => $dto->metadata,
            'apply_coupon' => $dto->applyCoupon,
        ], fn (mixed $value): bool => $value !== null);
    }

    public function createDtoFromResponse(Response $response): PaymentDTO
    {
        return PaymentDTO::fromResponse($response);
    }
}
