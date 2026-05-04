<?php

use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\UpdateInvoiceDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('updates an invoice', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

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
