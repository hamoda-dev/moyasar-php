<?php

namespace HamodaDev\Moyasar\Invoice\APIs\Pagination;

use HamodaDev\Moyasar\Invoice\DTO\InvoiceDTO;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;

final class ListInvoicesPaginator extends PagedPaginator
{
    protected function isLastPage(Response $response): bool
    {
        return $response->json('meta.next_page') === null;
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        $invoices = $response->json('invoices', []);

        return array_map(
            fn(array $invoice): InvoiceDTO => InvoiceDTO::fromArray($invoice),
            $invoices,
        );
    }
}
