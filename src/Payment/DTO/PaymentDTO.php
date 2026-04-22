<?php

namespace HamodaDev\Moyasar\Payment\DTO;

use Saloon\Http\Response;
use Saloon\Traits\Responses\HasResponse;

final readonly class PaymentDTO
{
    use HasResponse;

    /**
     * @param  array<string, mixed>  $metadata
     * @param  array<string, mixed>  $source
     */
    public function __construct(
        public string $id,
        public string $status,
        public int $amount,
        public int $fee,
        public string $currency,
        public int $refunded,
        public ?string $refundedAt,
        public int $captured,
        public ?string $capturedAt,
        public ?string $voidedAt,
        public string $description,
        public string $amountFormat,
        public string $feeFormat,
        public string $refundedFormat,
        public string $capturedFormat,
        public ?string $invoiceId,
        public ?string $ip,
        public ?string $callbackUrl,
        public string $createdAt,
        public string $updatedAt,
        public array $metadata,
        public array $source,
        public ?string $givenId = null,
    ) {}

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
            fee: (int) ($data['fee'] ?? 0),
            currency: $data['currency'],
            refunded: (int) ($data['refunded'] ?? 0),
            refundedAt: $data['refunded_at'] ?? null,
            captured: (int) ($data['captured'] ?? 0),
            capturedAt: $data['captured_at'] ?? null,
            voidedAt: $data['voided_at'] ?? null,
            description: $data['description'],
            amountFormat: $data['amount_format'],
            feeFormat: $data['fee_format'] ?? '',
            refundedFormat: $data['refunded_format'] ?? '',
            capturedFormat: $data['captured_format'] ?? '',
            invoiceId: $data['invoice_id'] ?? null,
            ip: $data['ip'] ?? null,
            callbackUrl: $data['callback_url'] ?? null,
            createdAt: $data['created_at'] ?? '',
            updatedAt: $data['updated_at'] ?? '',
            metadata: $data['metadata'] ?? [],
            source: $data['source'] ?? [],
            givenId: $data['given_id'] ?? null,
        );
    }
}
