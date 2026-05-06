<?php

namespace Tests\Config\Samples;

class InvoiceSamples
{
    /** @var string */
    public const MOCK_URL = "https://example.com";

    /** @var array */
    public const TEST_INVOICE = [
        'id' => '1234',
        'status' => 'initiated',
        'amount' => 1000,
        'currency' => 'USD',
        'description' => 'test order',
        'amount_format' => '10.00 USD',
        'url' => self::MOCK_URL,
        'metadata' => [
            'order_id' => '1234'
        ],
    ];

    /** @var array */
    public const array CANCELED_TEST_INVOICE = [
        'id' => '1234',
        'status' => 'canceled',
        'amount' => 1000,
        'currency' => 'USD',
        'description' => 'test order',
        'amount_format' => '10.00 USD',
        'url' => self::MOCK_URL,
        'metadata' => [
            'order_id' => '1234'
        ],
    ];

    /** @var array */
    public const array TEST_INVOICE_2 = [
        'id' => '5678',
        'status' => 'initiated',
        'amount' => 1000,
        'currency' => 'SAR',
        'description' => 'test order 22',
        'amount_format' => '10.00 SAR',
        'url' => self::MOCK_URL,
    ];

    /** @var array */
    public const array TEST_INVOICE_3 = [
        'id' => '91011',
        'status' => 'initiated',
        'amount' => 6900,
        'currency' => 'EGP',
        'description' => 'test order #333',
        'amount_format' => '69.00 EGP',
        'url' => self::MOCK_URL,
    ];
}
