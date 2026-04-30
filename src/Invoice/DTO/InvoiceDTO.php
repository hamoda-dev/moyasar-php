<?php

namespace HamodaDev\Moyasar\Invoice\DTO;

use Saloon\Http\Response;
use Saloon\Traits\Responses\HasResponse;

final class InvoiceDTO
{
    use HasResponse;

    /**
     * @param array<string, mixed> $metadata
     * @param array<string, mixed> $payments
     * Note: $logoUrl is not a part of the ListInvoice response, so, it's made nullable
     */
    public function __construct(
        public string $id,
        public string $status,
        public int $amount,
        public string $currency,
        public string $description,
        public ?string $logoUrl,
        public string $amountFormat,
        public string $url,
        public ?string $callbackUrl = null,
        public ?string $expiredAt = null,
        public string $createdAt = '',
        public string $updatedAt = '',
        public ?string $backUrl = null,
        public ?string $successUrl = null,
        public array $metadata = [],
        public array $payments = [],
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        return self::fromArray($response->json());
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: $data['status'],
            amount: (int) $data['amount'],
            currency: $data['currency'],
            description: $data['description'],
            logoUrl: $data['logo_url'] ?? null,
            amountFormat: $data['amount_format'],
            url: $data['url'],
            callbackUrl: $data['callback_url'] ?? null,
            expiredAt: $data['expired_at'] ?? null,
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            backUrl: $data['back_url'] ?? null,
            successUrl: $data['success_url'] ?? null,
            metadata: $data['metadata'] ?? [],
            payments: $data['payments'] ?? [],
        );
    }
}
