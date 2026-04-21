<?php

namespace HamodaDev\Moyasar\Invoice\DTO;

final readonly class UpdateInvoiceDTO
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public array $metadata,
    ) {}
}
