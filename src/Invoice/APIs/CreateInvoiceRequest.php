<?php

namespace HamodaDev\Moyasar\Invoice\APIs;

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        public readonly CreateInvoiceDTO $createInvoiceDTO,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/invoices';
    }

    public function defaultBody(): array
    {
        return array_filter([
            'amount' => $this->createInvoiceDTO->amount,
            'currency' => $this->createInvoiceDTO->currency,
            'description' => $this->createInvoiceDTO->description,
            'callback_url' => $this->createInvoiceDTO->callbackUrl,
            'success_url' => $this->createInvoiceDTO->successUrl,
            'back_url' => $this->createInvoiceDTO->backUrl,
            'expired_at' => $this->createInvoiceDTO->expiredAt,
            'metadata' => $this->createInvoiceDTO->metadata,
        ], fn (mixed $value): bool => $value !== null);
    }

    public function createDtoFromResponse(Response $response): InvoiceDTO
    {
        return InvoiceDTO::fromResponse($response);
    }
}
