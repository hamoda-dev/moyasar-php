<?php

namespace Tests\Config;

use Saloon\Http\Faking\MockResponse;
use Tests\Config\Samples\InvoiceSamples;
use Tests\Config\Samples\PaymentSamples;
use HamodaDev\Moyasar\Invoice\APIs\CancelInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\BulkCreateInvoicesRequest;
use HamodaDev\Moyasar\Invoice\APIs\CreateInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\GetInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\ListInvoicesRequest;
use HamodaDev\Moyasar\Invoice\APIs\UpdateInvoiceRequest;
use HamodaDev\Moyasar\Payment\APIs\CapturePaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\CreatePaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\GetPaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\ListPaymentsRequest;
use HamodaDev\Moyasar\Payment\APIs\RefundPaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\UpdatePaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\VoidPaymentRequest;

class MockResponses
{
    public static function getAll(): array
    {
        return array_merge(
            self::invoiceResponses(),
            self::paymentResponses(),
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

    private static function paymentResponses(): array
    {
        return [
            CreatePaymentRequest::class => MockResponse::make(body: PaymentSamples::TEST_PAYMENT),
            GetPaymentRequest::class => MockResponse::make(body: PaymentSamples::TEST_PAYMENT_3),
            ListPaymentsRequest::class => MockResponse::make(body: [
                'payments' => [
                    PaymentSamples::TEST_PAYMENT,
                    PaymentSamples::TEST_PAYMENT_2,
                    PaymentSamples::TEST_PAYMENT_3,
                ],
            ]),
            UpdatePaymentRequest::class => MockResponse::make(body: PaymentSamples::TEST_PAYMENT),
            RefundPaymentRequest::class => MockResponse::make(body: PaymentSamples::TEST_REFUNDED_PAYMENT),
            CapturePaymentRequest::class => MockResponse::make(body: PaymentSamples::TEST_CAPTURED_PAYMENT),
            VoidPaymentRequest::class => MockResponse::make(body: PaymentSamples::TEST_VOIDED_PAYMENT),
        ];
    }
}
