<?php

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\UpdatePaymentDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('updates a payment metadata', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO()));

    // act
    $updated = $moyasar->payment()->update($payment->id, new UpdatePaymentDTO(
        metadata: ['order_id' => '1234'],
    ));

    // assert
    expect($updated)->toBeInstanceOf(PaymentDTO::class)
        ->and($updated->id)->toBe($payment->id)
        ->and($updated->metadata)->toBe(['order_id' => '1234']);
});
