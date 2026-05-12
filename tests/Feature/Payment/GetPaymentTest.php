<?php

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('retrieves payment data', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO()));
    $paymentId = (getenv('REAL_TESTS') === 'true')
        ? $payment->id
        : $payment->id;

    // act
    $retrieved = $moyasar->payment()->get($paymentId);

    // assert
    expect($retrieved)
        ->toBeInstanceOf(PaymentDTO::class)
        ->and($retrieved->id)->not->toBeEmpty();
});

it('lists payments', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    // act
    $payments = iterator_to_array($moyasar->payment()->list()->paginate($moyasar)->items());

    // assert
    expect($payments)->not->toBeEmpty()
        ->and($payments[0])->toBeInstanceOf(PaymentDTO::class);
});
