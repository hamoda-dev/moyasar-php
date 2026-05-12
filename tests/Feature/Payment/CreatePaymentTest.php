<?php

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\Source\CreditCardSourceDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('creates a payment with credit card source', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    // act
    $payment = $moyasar->payment()->create(new CreatePaymentDTO(
        amount: 10000,
        currency: 'SAR',
        description: 'Test payment',
        callbackUrl: 'https://example.com/webhook',
        source: new CreditCardSourceDTO(
            name: 'John Doe',
            number: '4111111111111111',
            month: 12,
            year: 2030,
            cvc: 123,
        ),
    ));

    // assert
    expect($payment)->toBeInstanceOf(PaymentDTO::class)
        ->and($payment->id)->not->toBeEmpty()
        ->and($payment->status)->toBeIn(['initiated', 'paid'])
        ->and($payment->amount)->toBe(10000)
        ->and($payment->currency)->toBe('SAR')
        ->and($payment->source)->toBeArray()
        ->and($payment->source['type'])->toBe('creditcard');
});

it('creates a payment using fromArray with array source', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    // act
    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO([
        'amount' => 6900,
        'currency' => 'EGP',
    ])));

    // assert
    expect($payment)->toBeInstanceOf(PaymentDTO::class)
        ->and($payment->id)->not->toBeEmpty()
        ->and($payment->amount)->toBeIn([10000, 6900])
        ->and($payment->source)->toBeArray();
});
