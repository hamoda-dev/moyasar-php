<?php

namespace HamodaDev\Moyasar\Invoice\APIs;

use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetInvoiceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $invoiceId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/invoices/{$this->invoiceId}";
    }

    public function createDtoFromResponse(Response $response): InvoiceDTO
    {
        return InvoiceDTO::fromResponse($response);
    }
}
