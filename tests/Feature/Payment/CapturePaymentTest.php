<?php

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('captures an authorized payment', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO()));

    $paymentId = (getenv('REAL_TESTS') === 'true')
        ? getenv('MOYASAR_SAMPLE_AUTHORIZED_PAYMENT_ID') ?: $payment->id
        : $payment->id;

    // act
    $captured = $moyasar->payment()->capture($paymentId);

    // assert
    expect($captured)->toBeInstanceOf(PaymentDTO::class)
        ->and($captured->id)->toBe($paymentId)
        ->and($captured->status)->toBe('captured')
        ->and($captured->captured)->toBeGreaterThan(0)
        ->and($captured->capturedAt)->not->toBeNull();
})->skip(
    fn () => getenv('REAL_TESTS') === 'true' && empty(getenv('MOYASAR_SAMPLE_AUTHORIZED_PAYMENT_ID')),
    'MOYASAR_SAMPLE_AUTHORIZED_PAYMENT_ID is required for real capture tests'
);
