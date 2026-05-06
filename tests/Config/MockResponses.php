<?php

namespace Tests\Config;

use Saloon\Http\Faking\MockResponse;
use Tests\Config\Samples\InvoiceSamples;
use HamodaDev\Moyasar\Invoice\APIs\CancelInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\BulkCreateInvoicesRequest;
use HamodaDev\Moyasar\Invoice\APIs\CreateInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\GetInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\ListInvoicesRequest;
use HamodaDev\Moyasar\Invoice\APIs\UpdateInvoiceRequest;

class MockResponses
{
    public static function getAll(): array
    {
        return array_merge(
            self::invoiceResponses(),
            // self::paymentResponses(), // TODO: add when payment mocks are ready
        );
    }

    private static function invoiceResponses(): array
    {
        return [
            CreateInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::TEST_INVOICE),
            BulkCreateInvoicesRequest::class => MockResponse::make(body: [
                'invoices' => [
                    InvoiceSamples::TEST_INVOICE,
                    InvoiceSamples::TEST_INVOICE_2,
                ],
            ]),
            GetInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::TEST_INVOICE_3),
            ListInvoicesRequest::class => MockResponse::make(body: [
                'invoices' => [
                    InvoiceSamples::TEST_INVOICE,
                    InvoiceSamples::TEST_INVOICE_2,
                    InvoiceSamples::TEST_INVOICE_3,
                ],
            ]),
            UpdateInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::TEST_INVOICE),
            CancelInvoiceRequest::class => MockResponse::make(body: InvoiceSamples::CANCELED_TEST_INVOICE),
        ];
    }
}
