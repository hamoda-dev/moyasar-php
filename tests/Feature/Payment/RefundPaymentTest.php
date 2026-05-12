<?php

use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Tests\Config\MoyasarInitializer;

beforeAll(fn() => validateEnvIsSet());

it('refunds a full payment', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO()));

    $paymentId = (getenv('REAL_TESTS') === 'true')
        ? getenv('MOYASAR_SAMPLE_PAID_PAYMENT_ID') ?: $payment->id
        : $payment->id;

    // act
    $refunded = $moyasar->payment()->refund($paymentId);

    // assert
    expect($refunded)->toBeInstanceOf(PaymentDTO::class)
        ->and($refunded->id)->toBe($paymentId)
        ->and($refunded->status)->toBe('refunded')
        ->and($refunded->refunded)->toBeGreaterThan(0)
        ->and($refunded->refundedAt)->not->toBeNull();
})->skip(
    fn () => getenv('REAL_TESTS') === 'true' && empty(getenv('MOYASAR_SAMPLE_PAID_PAYMENT_ID')),
    'MOYASAR_SAMPLE_PAID_PAYMENT_ID is required for real refund tests'
);

it('refunds a partial payment', function () {
    // arrange
    $moyasar = MoyasarInitializer::getInstance()->getMoyasar();

    $payment = $moyasar->payment()->create(CreatePaymentDTO::fromArray(mockCreatePaymentDTO()));

    $paymentId = (getenv('REAL_TESTS') === 'true')
        ? getenv('MOYASAR_SAMPLE_PAID_PAYMENT_ID') ?: $payment->id
        : $payment->id;

    // act
    $refunded = $moyasar->payment()->refund($paymentId, 5000);

    // assert
    expect($refunded)->toBeInstanceOf(PaymentDTO::class)
        ->and($refunded->id)->toBe($paymentId)
        ->and($refunded->status)->toBe('refunded');
})->skip(
    fn () => getenv('REAL_TESTS') === 'true' && empty(getenv('MOYASAR_SAMPLE_PAID_PAYMENT_ID')),
    'MOYASAR_SAMPLE_PAID_PAYMENT_ID is required for real refund tests'
);
