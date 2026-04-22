<?php

namespace HamodaDev\Moyasar\Payment\DTO;

use HamodaDev\Moyasar\Payment\DTO\Source\CreditCardSourceDTO;

final readonly class CreatePaymentDTO
{
    /**
     * @param  CreditCardSourceDTO|array<string, mixed>  $source
     * @param  array<string, mixed>|null  $metadata
     */
    public function __construct(
        public int $amount,
        public string $currency,
        public string $description,
        public CreditCardSourceDTO|array $source,
        public ?string $givenId = null,
        public ?string $callbackUrl = null,
        public ?array $metadata = null,
        public ?bool $applyCoupon = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            amount: (int) $data['amount'],
            currency: $data['currency'],
            description: $data['description'],
            source: $data['source'],
            givenId: $data['given_id'] ?? null,
            callbackUrl: $data['callback_url'] ?? null,
            metadata: $data['metadata'] ?? null,
            applyCoupon: $data['apply_coupon'] ?? null,
        );
    }
}
