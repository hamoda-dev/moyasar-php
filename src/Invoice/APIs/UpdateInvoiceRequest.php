<?php

namespace HamodaDev\Moyasar\Invoice\APIs;

use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\UpdateInvoiceDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateInvoiceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        public readonly string $invoiceId,
        public readonly UpdateInvoiceDTO $updateInvoiceDTO,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->invoiceId}";
    }

    public function defaultBody(): array
    {
        return [
            'metadata' => $this->updateInvoiceDTO->metadata,
        ];
    }

    public function createDtoFromResponse(Response $response): InvoiceDTO
    {
        return InvoiceDTO::fromResponse($response);
    }
}
