<?php

namespace HamodaDev\Moyasar\Invoice\DTO;

final readonly class CreateInvoiceDTO
{
    public function __construct(
        public int $amount,
        public string $currency,
        public string $description,
        public ?string $callbackUrl = null,
        public ?string $successUrl = null,
        public ?string $backUrl = null,
        public ?string $expiredAt = null,
        public ?array $metadata = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            amount: (int) $data['amount'],
            currency: $data['currency'],
            description: $data['description'],
            callbackUrl: $data['callback_url'] ?? null,
            successUrl: $data['success_url'] ?? null,
            backUrl: $data['back_url'] ?? null,
            expiredAt: $data['expired_at'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }
}
