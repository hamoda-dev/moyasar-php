<?php

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Moyasar;

// don't use callable syntax since it won't apply to this file and no validation will happen
beforeAll(fn() => validateEnvIsSet());

it('Creates an invoice and send the customer to the hosted payment page', function () {
    // arrange
    $moyasar = new Moyasar(
        baseUrl: getenv('MOYASAR_BASE_URL'),
        apiKey: getenv('MOYASAR_SECRET_KEY'),
    );

    // act
    $invoice = $moyasar->invoice()->create(new CreateInvoiceDTO(
        amount: 1000,
        currency: 'USD',
        description: 'Order #1234',
        callbackUrl: 'https://example.com/webhooks/moyasar',
    ));

    // assert
    expect($invoice)->toBeInstanceOf(InvoiceDTO::class)
        ->and($invoice->id)->not->toBeEmpty()
        ->and($invoice->status)->toBe('initiated')
        ->and($invoice->amount)->toBe(1000)
        ->and($invoice->currency)->toBe('USD')
        ->and($invoice->url)->toStartWith('https://');
});

it('Bulk creates invoices', function () {
    // arrange
    $moyasar = new Moyasar(
        baseUrl: getenv('MOYASAR_BASE_URL'),
        apiKey: getenv('MOYASAR_SECRET_KEY'),
    );

    // act
    $invoices = $moyasar->invoice()->bulkCreate([
        CreateInvoiceDTO::fromArray(mockCreateInvoiceDTO(['amount' => 69_00, 'currency' => 'USD'])),
        CreateInvoiceDTO::fromArray(mockCreateInvoiceDTO(['amount' => 70_00, 'currency' => 'EGP'])),
    ]);

    // assert
    expect($invoices['invoices'])->toBeArray()->toHaveCount(2)
        ->and($invoices['invoices'][0])->toBeInstanceOf(InvoiceDTO::class)
        ->and($invoices['invoices'][0]->id)->not->toBeEmpty()
        ->and($invoices['invoices'][1]->id)->not->toBeEmpty();
});
