<?php

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\UpdateInvoiceDTO;
use HamodaDev\Moyasar\Moyasar;

beforeAll(fn() => validateEnvIsSet());

it('updates an invoice', function () {
    // arrange
    $moyasar = new Moyasar(
        baseUrl: getenv('MOYASAR_BASE_URL'),
        apiKey: getenv('MOYASAR_SECRET_KEY'),
    );

    $invoice = $moyasar->invoice()->create(CreateInvoiceDTO::fromArray(mockCreateInvoiceDTO()));

    // act
    $updated = $moyasar->invoice()->update($invoice->id, new UpdateInvoiceDTO(
        metadata: ['order_id' => '1234'],
    ));

    // assert
    expect($updated)->toBeInstanceOf(InvoiceDTO::class)
        ->and($updated->id)->toBe($invoice->id)
        ->and($updated->metadata)->toBe(['order_id' => '1234']);
});
