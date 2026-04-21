<?php

namespace HamodaDev\Moyasar\Invoice;

use HamodaDev\Moyasar\Invoice\APIs\BulkCreateInvoicesRequest;
use HamodaDev\Moyasar\Invoice\APIs\CancelInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\CreateInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\GetInvoiceRequest;
use HamodaDev\Moyasar\Invoice\APIs\ListInvoicesRequest;
use HamodaDev\Moyasar\Invoice\APIs\UpdateInvoiceRequest;
use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use HamodaDev\Moyasar\Invoice\DTO\UpdateInvoiceDTO;
use Saloon\Http\BaseResource;

class InvoiceResource extends BaseResource
{
    public function get(string $invoiceId): InvoiceDTO
    {
        return $this->connector->send(new GetInvoiceRequest($invoiceId))->dto();
    }

    public function list(): ListInvoicesRequest
    {
        return new ListInvoicesRequest;
    }

    public function create(CreateInvoiceDTO $dto): InvoiceDTO
    {
        return $this->connector->send(new CreateInvoiceRequest($dto))->dto();
    }

    public function update(string $invoiceId, UpdateInvoiceDTO $dto): InvoiceDTO
    {
        return $this->connector->send(new UpdateInvoiceRequest($invoiceId, $dto))->dto();
    }

    public function cancel(string $invoiceId): InvoiceDTO
    {
        return $this->connector->send(new CancelInvoiceRequest($invoiceId))->dto();
    }

    public function bulkCreate(array $invoices): mixed
    {
        return $this->connector->send(new BulkCreateInvoicesRequest($invoices))->dto();
    }
}
