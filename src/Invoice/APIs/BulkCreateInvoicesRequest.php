<?php

namespace HamodaDev\Moyasar\Invoice\APIs;

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class BulkCreateInvoicesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<CreateInvoiceDTO>  $invoices
     */
    public function __construct(
        public readonly array $invoices,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/invoices/bulk';
    }

    public function defaultBody(): array
    {
        return [
            'invoices' => array_map(
                fn(CreateInvoiceDTO $dto): array => array_filter([
                    'amount' => $dto->amount,
                    'currency' => $dto->currency,
                    'description' => $dto->description,
                    'callback_url' => $dto->callbackUrl,
                    'success_url' => $dto->successUrl,
                    'back_url' => $dto->backUrl,
                    'expired_at' => $dto->expiredAt,
                    'metadata' => $dto->metadata,
                ], fn(mixed $value): bool => $value !== null),
                $this->invoices,
            ),
        ];
    }

    /**
     * @param Response $response
     * @return array{invoices: InvoiceDTO[]}
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = [];
        $data['invoices'] = array_map(
            fn(array $invoice): InvoiceDTO => InvoiceDTO::fromArray($invoice),
            $response->json('invoices', [])
        );
        return $data;
    }
}
