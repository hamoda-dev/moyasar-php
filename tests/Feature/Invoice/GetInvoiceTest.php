<?php

use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Moyasar;

beforeAll(fn() => validateEnvIsSet());

it('retrieves invoice data', function () {
    // arrange
    $moyasar = new Moyasar(
        baseUrl: getenv('MOYASAR_BASE_URL'),
        apiKey: getenv('MOYASAR_SECRET_KEY'),
    );

    // act
    $invoice = $moyasar->invoice()->get(getenv('MOYASAR_SAMPLE_INITIATED_INVOICE_ID'));

    // assert
    expect($invoice)
        ->toBeInstanceOf(InvoiceDTO::class)
        ->and($invoice->status)->toBe('initiated');
});

it('lists invoices', function () {
    // arrange
    $moyasar = new Moyasar(
        baseUrl: getenv('MOYASAR_BASE_URL'),
        apiKey: getenv('MOYASAR_SECRET_KEY'),
    );

    // act
    $invoices = iterator_to_array($moyasar->invoice()->list()->paginate($moyasar)->items());

    // assert
    expect($invoices)->not->toBeEmpty()
        ->and($invoices[0])->toBeInstanceOf(InvoiceDTO::class);
});
