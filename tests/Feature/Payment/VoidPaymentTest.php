<?php

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('voids a payment', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO()));

    $paymentId = (getenv('REAL_TESTS') === 'true')
        ? getenv('MOYASAR_SAMPLE_AUTHORIZED_PAYMENT_ID') ?: $payment->id
        : $payment->id;

    // act
    $voided = $moyasar->payment()->void($paymentId);

    // assert
    expect($voided)->toBeInstanceOf(PaymentDTO::class)
        ->and($voided->id)->toBe($paymentId)
        ->and($voided->status)->toBe('voided')
        ->and($voided->voidedAt)->not->toBeNull()
        ->and($voided->captured)->toBe(0);
})->skip(
    fn () => getenv('REAL_TESTS') === 'true' && empty(getenv('MOYASAR_SAMPLE_AUTHORIZED_PAYMENT_ID')),
    'MOYASAR_SAMPLE_AUTHORIZED_PAYMENT_ID is required for real void tests'
);
