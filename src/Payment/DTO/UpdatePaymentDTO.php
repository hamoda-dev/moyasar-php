<?php

namespace HamodaDev\Moyasar\Payment\DTO;

final readonly class UpdatePaymentDTO
{
    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function __construct(
        public ?string $description = null,
        public ?array $metadata = null,
    ) {}
}
