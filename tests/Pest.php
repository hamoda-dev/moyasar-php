<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use HamodaDev\Moyasar\Moyasar;
use Saloon\Http\Faking\MockClient;
use Tests\TestCase;

pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

uses()
    ->beforeEach(fn () => MockClient::destroyGlobal())
    ->in(__DIR__);

function validateEnvIsSet()
{
    $keys = [
        'MOYASAR_SECRET_KEY',
        'MOYASAR_BASE_URL',
        'MOYASAR_SAMPLE_INITIATED_INVOICE_ID'
    ];

    $isEnvSet = true;
    foreach ($keys as $k) {
        $isEnvSet = $isEnvSet && !empty(getenv($k));
    }

    if (!$isEnvSet) {
        throw new UnexpectedValueException("Environment Variables are not Set");
    }
}

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function mockMoyasar(): Moyasar
{
    return new Moyasar(
        baseUrl: 'https://api.moyasar.com/v1',
        apiKey: 'sk_test_abc123',
    );
}

function mockCreateInvoiceDTO(array $overrides = []): array
{
    return [
        'amount' => 2500,
        'currency' => 'SAR',
        'description' => 'Order #1234',
        ...$overrides
    ];
}

function mockInvoiceDTO(array $overrides = []): array
{
    return [
        'id' => 'inv_abc123',
        'status' => 'initiated',
        'amount' => 2500,
        'currency' => 'SAR',
        'description' => 'Order #1234',
        'logo_url' => 'https://cdn.moyasar.com/logo.png',
        'amount_format' => '25.00 SAR',
        'url' => 'https://invoices.moyasar.com/inv_abc123',
        'callback_url' => null,
        'expired_at' => null,
        'created_at' => '2024-01-01T10:00:00Z',
        'updated_at' => '2024-01-01T10:00:00Z',
        'back_url' => null,
        'success_url' => null,
        'metadata' => [],
        'payments' => [],
        ...$overrides
    ];
}

function mockCreatePaymentDTO(array $overrides = []): array
{
    return [
        'amount' => 10000,
        'currency' => 'SAR',
        'description' => 'Test payment',
        'callback_url' => 'https://example.com/webhook',
        'source' => [
            'type' => 'creditcard',
            'name' => 'John Doe',
            'number' => '4111111111111111',
            'month' => 12,
            'year' => 2030,
            'cvc' => 123,
        ],
        ...$overrides
    ];
}

function mockPaymentDTO(array $overrides = []): array
{
    return [
        'id' => 'pay_1',
        'status' => 'paid',
        'amount' => 10000,
        'fee' => 50,
        'currency' => 'SAR',
        'refunded' => 0,
        'refunded_at' => null,
        'captured' => 10000,
        'captured_at' => '2024-01-15T12:00:00Z',
        'voided_at' => null,
        'description' => 'Test payment',
        'amount_format' => '100.00 SAR',
        'fee_format' => '0.50 SAR',
        'refunded_format' => '0.00 SAR',
        'captured_format' => '100.00 SAR',
        'invoice_id' => null,
        'ip' => '127.0.0.1',
        'callback_url' => null,
        'created_at' => '2024-01-15T10:00:00Z',
        'updated_at' => '2024-01-15T12:00:00Z',
        'metadata' => [],
        'source' => [
            'type' => 'creditcard',
            'company' => 'visa',
            'name' => 'John Doe',
            'number' => '**** **** **** 1111',
            'message' => null,
        ],
        'given_id' => null,
        ...$overrides
    ];
}
