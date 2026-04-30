# Pull Request: Minor Bug Fixes & Adding Tests

This pull request adds unit and integration tests and fixes small bugs

- [ ] Breaking Change ❌
- New version: 0.0.2

## Bugs

1. There was no implementation for `createDtoFromResponse` in [`BulkCreateInvoicesRequest.php`](../../src/Invoice/APIs/BulkCreateInvoicesRequest.php). Implementation was added
2. When viewing paginated invoices, we got a response like this

```php
Array
(
    [invoices] => Array
        (
            [0] => Array
                (
                    [id] => 659f9c29-5fd5-325b-b9df-bac86090d04e
                    [status] => initiated
                    [amount] => 1000
                    [currency] => USD
                    [description] => Order #1234
                    [amount_format] => 10.00 USD
                    [url] => https://checkout.moyasar.com/invoices/659f9c29-5fd5-325b-b9df-bac86090d04e?lang=en
                    [callback_url] => https://example.com/webhooks/moyasar
                    [expired_at] =>
                    [created_at] => 2026-04-30T17:22:57.186Z
                    [updated_at] => 2026-04-30T17:22:57.186Z
                    [back_url] =>
                    [success_url] =>
                    [payment_id] =>
                    [paid_at] =>
                    [metadata] =>
                )
        )

    [meta] => Array
        (
            [current_page] => 2
            [next_page] =>
            [prev_page] => 1
            [total_pages] => 2
            [total_count] => 44
        )

)
```

Which causes issues with the current InvoiceDTO, because logoUrl is null. So, I \[temporarly\] marked `$logoUrl` to be nullable

## Tests

- I \[Abdurrahman\] personally don't like mocking integration tests. However, since I don't have a saudi phone number, I cannot create a Moyasar platform account and thus cannot use test keys. **I highly recommend changing tests to use test environments intead of mocking whenever possible in the fututre**

## Files & Directories Added

- `tests/` directory
