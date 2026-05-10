<?php

namespace HamodaDev\Moyasar\Invoice\Shared\Models;

use DateTime;
use HamodaDev\Moyasar\Shared\Const\Currency;

/**
 * @phpstan-type KeyValuePair array<string, mixed>
 * @phpstan-type InvoiceArray array{
 *    amount: float,
 *    currency: Currency,
 *    description: string,
 *    callback_url?: ?string,
 *    success_url?: ?string,
 *    back_url?: ?string,
 *    expired_at?: ?DateTime,
 *    metadata?: array<string, mixed>
 *}
 */
final class Invoice
{
    public function __construct(
        private float $amount,
        private Currency $currency,
        private string $description,
        private ?string $callbackUrl = null,
        private ?string $successUrl = null,
        private ?string $backUrl = null,
        private ?DateTime $expiredAt = null,
        private ?array $metadata = null,
    ) {
    }

    public static function create(
        float $amount,
        Currency $currency,
        string $description,
        ?string $callbackUrl = null,
        ?string $successUrl = null,
        ?string $backUrl = null,
        ?DateTime $expiredAt = null,
        ?array $metadata = null,
    ): self {
        return new self($amount, $currency, $description, $callbackUrl, $successUrl, $backUrl, $expiredAt, $metadata);
    }

    /**
     * @return InvoiceArray
     */
    public function toRequestArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'callback_url' => $this->callbackUrl,
            'success_url' => $this->successUrl,
            'back_url' => $this->backUrl,
            'expired_at' => $this->expiredAt,
            'metadata' => $this->metadata,
        ];
    }

    // Getters
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getdescription(): string
    {
        return $this->description;
    }

    public function getcallbackUrl(): ?string
    {
        return $this->callbackUrl;
    }

    public function getsuccessUrl(): ?string
    {
        return $this->successUrl;
    }

    public function getbackUrl(): ?string
    {
        return $this->backUrl;
    }

    public function getexpiredAt(): ?DateTime
    {
        return $this->expiredAt;
    }

    public function getmetadata(): ?array
    {
        return $this->metadata;
    }
}
