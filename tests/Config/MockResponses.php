<?php

namespace Tests\Config;

use Saloon\Http\Faking\MockResponse;
use Tests\Config\Samples\InvoiceSamples;

return [
    // invoice
    \HamodaDev\Moyasar\Invoice\APIs\CreateInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::TEST_INVOICE),
    \HamodaDev\Moyasar\Invoice\APIs\BulkCreateInvoicesRequest::class => MockResponse::make(body: [
        'invoices' => [InvoiceSamples::TEST_INVOICE, InvoiceSamples::TEST_INVOICE_2]
    ]),
    \HamodaDev\Moyasar\Invoice\APIs\GetInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::TEST_INVOICE_3),
    \HamodaDev\Moyasar\Invoice\APIs\ListInvoicesRequest::class => MockResponse::make(body: [
        'invoices' => [InvoiceSamples::TEST_INVOICE, InvoiceSamples::TEST_INVOICE_2, InvoiceSamples::TEST_INVOICE_3],
    ]),
    \HamodaDev\Moyasar\Invoice\APIs\UpdateInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::TEST_INVOICE),
    \HamodaDev\Moyasar\Invoice\APIs\CancelInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::CANCELED_TEST_INVOICE),

    // payment
    /** TODO */
];

