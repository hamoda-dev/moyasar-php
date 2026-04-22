<?php

namespace HamodaDev\Moyasar\Payment\APIs\Pagination;

use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;

final class ListPaymentsPaginator extends PagedPaginator
{
    protected function isLastPage(Response $response): bool
    {
        return $response->json('meta.next_page') === null;
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        $payments = $response->json('payments', []);

        return array_map(
            fn (array $payment): PaymentDTO => PaymentDTO::fromArray($payment),
            $payments,
        );
    }
}
