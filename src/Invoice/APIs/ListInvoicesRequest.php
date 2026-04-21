<?php

namespace HamodaDev\Moyasar\Invoice\APIs;

use HamodaDev\Moyasar\Invoice\APIs\Pagination\ListInvoicesPaginator;
use Saloon\Enums\Method;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\HasRequestPagination;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\PaginationPlugin\Paginator;

class ListInvoicesRequest extends Request implements HasRequestPagination, Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/invoices';
    }

    public function paginate(Connector $connector): Paginator
    {
        return new ListInvoicesPaginator($connector, $this);
    }
}
