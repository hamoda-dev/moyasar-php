<?php

namespace HamodaDev\Moyasar\Payment\APIs;

use HamodaDev\Moyasar\Payment\APIs\Pagination\ListPaymentsPaginator;
use Saloon\Enums\Method;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\HasRequestPagination;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\PaginationPlugin\Paginator;

class ListPaymentsRequest extends Request implements HasRequestPagination, Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/payments';
    }

    public function paginate(Connector $connector): Paginator
    {
        return new ListPaymentsPaginator($connector, $this);
    }
}
