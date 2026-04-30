# Pull Request: Minor Bug Fixes & Adding Tests

This pull request adds unit and integration tests and fixes small bugs

- [ ] Breaking Change ❌
- [x] **New version: v0.0.2**

## Files & Directories Added

- `tests/` directory
- `phpunit.xml.dist` boilerplate for Pest
    - **Note: When cloning the repo, copy `phpunit.xml.dist` into a new file `phpunit.xml` and fill it with your environment variables**
- `docs/` for brainstorming and documentations

## Bugs

1. There was no implementation for `createDtoFromResponse` in [`BulkCreateInvoicesRequest.php`](../../src/Invoice/APIs/BulkCreateInvoicesRequest.php). Implementation was added

    ```php
    /**
     * @param Response $response
     * @return array{invoices: InvoiceDTO[]}
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = [];
        $data['invoices'] = array_map(
            fn(array $invoice): InvoiceDTO => InvoiceDTO::fromArray($invoice),
            $response->json('invoices', [])
        );
        return $data;
    }
    ```

2. When viewing paginated invoices, we got a response like this

    ```php
    [
        'invoices' => [
            [
                'id' => '659f9c29-5fd5-325b-b9df-bac86090d04e',
                'status' => 'initiated',
                'amount' => 1000,
                'currency' => 'USD',
                'description' => 'Order #1234',
                'amount_format' => '10.00 USD',
                'url' => 'https://checkout.moyasar.com/invoices/659f9c29-5fd5-325b-b9df-bac86090d04e?lang=en',
                'callback_url' => 'https://example.com/webhooks/moyasar',
                'expired_at' => null,
                'created_at' => '2026-04-30T17:22:57.186Z',
                'updated_at' => '2026-04-30T17:22:57.186Z',
                'back_url' => null,
                'success_url' => null,
                'payment_id' => null,
                'paid_at' => null,
                'metadata' => null,
            ],
            // ... rest of invoices
        ],
        'meta' => [
            'current_page' => 2,
            'next_page' =>,
            'prev_page' => 1,
            'total_pages' => 2,
            'total_count' => 44,
        ]
    ]
    ```

Which causes issues with the current InvoiceDTO, because logoUrl is null. So, I \[temporarly\] marked `$logoUrl` to be nullable

## Tests

- I \[Abdurrahman\] have created unverified, throwaway dummy account in Moyaser platform only for testing. I preferred this approach over mocking, and it's safe anyway since the credentials are not anywhere but on a local machine (temporary email and a password), feel free to fill your test credentials in phpunit.xml

## Next Steps

- Adding Payment Integration Tests: I intentionally added only invoice tests in this PR so that the cognitive load is easy to understand
