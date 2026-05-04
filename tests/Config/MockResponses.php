<?php

use Saloon\Http\Faking\MockResponse;

const MOCK_URL = 'https://example.com';

const TEST_INVOICE = [
    'id' => '1234',
    'status' => 'initiated',
    'amount' => 1000,
    'currency' => 'USD',
    'description' => 'test order',
    'amount_format' => '10.00 USD',
    'url' => MOCK_URL,
    'metadata' => [
        'order_id' => '1234'
    ],
];

const CANCELED_TEST_INVOICE = [
    ...TEST_INVOICE,
    'status' => 'canceled',
];

const TEST_INVOICE_2 = [
    'id' => '5678',
    'status' => 'initiated',
    'amount' => 1000,
    'currency' => 'SAR',
    'description' => 'test order 22',
    'amount_format' => '10.00 SAR',
    'url' => MOCK_URL,
];

const TEST_INVOICE_3 = [
    'id' => '91011',
    'status' => 'initiated',
    'amount' => 6900,
    'currency' => 'EGP',
    'description' => 'test order #333',
    'amount_format' => '69.00 EGP',
    'url' => MOCK_URL,
];

return [
    // invoice
    \HamodaDev\Moyasar\Invoice\APIs\CreateInvoiceRequest::class => MockResponse::make(body: TEST_INVOICE),
    \HamodaDev\Moyasar\Invoice\APIs\BulkCreateInvoicesRequest::class => MockResponse::make(body: [TEST_INVOICE, TEST_INVOICE_2]),
    \HamodaDev\Moyasar\Invoice\APIs\GetInvoiceRequest::class => MockResponse::make(body: TEST_INVOICE_3),
    \HamodaDev\Moyasar\Invoice\APIs\ListInvoicesRequest::class => MockResponse::make(body: [
        'invoices' => [TEST_INVOICE, TEST_INVOICE_2, TEST_INVOICE_3],
    ]),
    \HamodaDev\Moyasar\Invoice\APIs\UpdateInvoiceRequest::class => MockResponse::make(body: TEST_INVOICE),
    \HamodaDev\Moyasar\Invoice\APIs\CancelInvoiceRequest::class => MockResponse::make(body: CANCELED_TEST_INVOICE),

    // payment
    /** TODO */
];
