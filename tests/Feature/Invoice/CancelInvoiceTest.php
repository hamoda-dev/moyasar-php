<?php

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('cancels an invoice', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $invoice = $moyasar->invoice()->create(CreateInvoiceDTO::fromArray(mockCreateInvoiceDTO()));

    // act
    $cancelled = $moyasar->invoice()->cancel($invoice->id);

    // assert
    expect($cancelled)->toBeInstanceOf(InvoiceDTO::class)
        ->and($cancelled->id)->toBe($invoice->id)
        ->and($cancelled->status)->toBe('canceled');
});
