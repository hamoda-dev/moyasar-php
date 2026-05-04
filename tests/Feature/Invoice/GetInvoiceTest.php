<?php

use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('retrieves invoice data', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    // act
    $invoice = $moyasar->invoice()->get(getenv('MOYASAR_SAMPLE_INITIATED_INVOICE_ID') ?: '91011');

    // assert
    expect($invoice)
        ->toBeInstanceOf(InvoiceDTO::class)
        ->and($invoice->status)->toBe('initiated');
});

it('lists invoices', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    // act
    $invoices = iterator_to_array($moyasar->invoice()->list()->paginate($moyasar)->items());

    // assert
    expect($invoices)->not->toBeEmpty()
        ->and($invoices[0])->toBeInstanceOf(InvoiceDTO::class);
});
