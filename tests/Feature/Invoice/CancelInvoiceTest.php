<?php

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Moyasar;

beforeAll(fn() => validateEnvIsSet());

it('cancels an invoice', function () {
    // arrange
    $moyasar = new Moyasar(
        baseUrl: getenv('MOYASAR_BASE_URL'),
        apiKey: getenv('MOYASAR_SECRET_KEY'),
    );

    $invoice = $moyasar->invoice()->create(CreateInvoiceDTO::fromArray(mockCreateInvoiceDTO()));

    // act
    $cancelled = $moyasar->invoice()->cancel($invoice->id);

    // assert
    expect($cancelled)->toBeInstanceOf(InvoiceDTO::class)
        ->and($cancelled->id)->toBe($invoice->id)
        ->and($cancelled->status)->toBe('canceled');
});
