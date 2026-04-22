<?php

namespace HamodaDev\Moyasar\Payment\DTO\Source;

final readonly class CreditCardSourceDTO
{
    public function __construct(
        public string $name,
        public string $number,
        public int $month,
        public int $year,
        public int $cvc,
        public string $type = 'creditcard',
        public ?string $statementDescriptor = null,
        public ?bool $threeDs = null,
        public ?bool $manual = null,
        public ?bool $saveCard = null,
        public ?string $token = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'name' => $this->name,
            'number' => $this->number,
            'month' => $this->month,
            'year' => $this->year,
            'cvc' => $this->cvc,
            'statement_descriptor' => $this->statementDescriptor,
            '3ds' => $this->threeDs,
            'manual' => $this->manual,
            'save_card' => $this->saveCard,
            'token' => $this->token,
        ], fn (mixed $value): bool => $value !== null);
    }
}
