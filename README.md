# Moyasar PHP
 
A simple, expressive PHP client for the [Moyasar](https://moyasar.com) payment gateway.
 
This package provides a clean interface for working with Moyasar's Invoices and Payments APIs in any PHP 8.2+ application. It's framework-agnostic and works great with Laravel, Symfony, or plain PHP.
 
## Requirements
 
- PHP **8.2+**
- [Saloon](https://docs.saloon.dev) `^4.0`
- [Saloon Pagination Plugin](https://docs.saloon.dev/docs/the-pagination-plugin) `^2.3`
## Installation
 
Install via Composer:
 
```bash
composer require hamoda-dev/moyasar-php
```
 
That's it. No service providers to register, no config files to publish. The package is framework-agnostic — use it in Laravel, Symfony, Slim, or plain PHP.
 
## Quick Start
 
Grab your secret key from the [Moyasar dashboard](https://dashboard.moyasar.com), then:
 
```php
use HamodaDev\Moyasar\Moyasar;
use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
 
$moyasar = new Moyasar(
    baseUrl: 'https://api.moyasar.com/v1',
    apiKey:  getenv('MOYASAR_SECRET_KEY'),
);
 
// Create an invoice and send the customer to the hosted payment page
$invoice = $moyasar->invoice()->create(new CreateInvoiceDTO(
    amount:      2500,                 // 25.00 SAR — always in the smallest unit
    currency:    'SAR',
    description: 'Order #1234',
    callbackUrl: 'https://example.com/webhooks/moyasar',
));
 
header("Location: {$invoice->url}");
```
 
Three lines to take a payment. No Guzzle, no array-shuffling, no JSON decoding.
 
### Recommended Environment Setup
 
Keep credentials out of your code:
 
```env
MOYASAR_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxx
MOYASAR_BASE_URL=https://api.moyasar.com/v1
```
 
> [!WARNING]
> **Never commit API keys to version control.** Use environment variables, a secrets manager, or your framework's config system. Treat your secret key like a password.
 
> [!TIP]
> Moyasar issues separate **test** (`sk_test_...`) and **live** (`sk_live_...`) keys. Use the test key in development and staging — you can run real payment flows against test cards without charging anyone.
 
---
 
## Invoices
 
Invoices are the fastest way to accept a payment: you create one, send the customer to `invoice->url`, and Moyasar handles the entire checkout UI for you.
 
### Create an Invoice
 
```php
use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;
 
$invoice = $moyasar->invoice()->create(new CreateInvoiceDTO(
    amount:      2500,
    currency:    'SAR',
    description: 'Order #1234',
    callbackUrl: 'https://example.com/webhooks/moyasar',
    successUrl:  'https://example.com/payment/success',
    backUrl:     'https://example.com/payment/cancel',
    metadata:    ['order_id' => '1234'],
));
 
echo $invoice->url;     // Hosted payment page — redirect your user here
echo $invoice->id;      // Store this alongside your order
echo $invoice->status;  // "initiated" for a fresh invoice
```
 
| Parameter | Type | Required | What it's for |
| --- | --- | --- | --- |
| `amount` | `int` | Yes | Smallest currency unit (halalas for SAR, cents for USD) |
| `currency` | `string` | Yes | ISO 4217 code (`SAR`, `USD`, ...) |
| `description` | `string` | Yes | Shown to the customer on the payment page |
| `callbackUrl` | `?string` | No | Webhook URL — Moyasar POSTs here when payment status changes |
| `successUrl` | `?string` | No | Where to redirect after a successful payment |
| `backUrl` | `?string` | No | Where to redirect if the customer cancels |
| `expiredAt` | `?string` | No | ISO 8601 — invoice auto-expires after this |
| `metadata` | `?array` | No | Arbitrary key-value data — perfect for your internal IDs |
 
Prefer building DTOs from incoming request data? Use the array factory:
 
```php
$dto = CreateInvoiceDTO::fromArray($request->validated());
$invoice = $moyasar->invoice()->create($dto);
```
 
### Retrieve an Invoice
 
```php
$invoice = $moyasar->invoice()->get('invoice_12345');
 
if ($invoice->status === 'paid') {
    // Fulfill the order
}
```
 
### List Invoices (with Pagination)
 
Moyasar returns invoices in pages. The SDK's paginator handles page-walking for you — no manual `?page=N` tracking:
 
```php
$paginator = $moyasar->invoice()->list()->paginate($moyasar);
 
while ($paginator->hasMorePages()) {
    foreach ($paginator->items() as $invoice) {
        echo "{$invoice->id} — {$invoice->status}\n";
    }
 
    $paginator = $paginator->nextPage();
}
```
 
> [!TIP]
> Every item yielded by `items()` is a fully-typed `InvoiceDTO`. Your IDE will autocomplete `->id`, `->status`, `->amount`, and every other field.
 
### Update an Invoice
 
Only `metadata` is updatable after creation — use this to attach internal context as your order progresses:
 
```php
use HamodaDev\Moyasar\Invoice\DTO\UpdateInvoiceDTO;
 
$moyasar->invoice()->update('invoice_12345', new UpdateInvoiceDTO(
    metadata: [
        'order_id'     => '1234',
        'fulfilled_at' => now()->toIso8601String(),
    ],
));
```
 
### Bulk-Create Invoices
 
Need to send 50 invoices for a batch of orders? One request, one round-trip:
 
```php
$result = $moyasar->invoice()->bulkCreate([
    new CreateInvoiceDTO(amount: 1000, currency: 'SAR', description: 'Invoice A'),
    new CreateInvoiceDTO(amount: 2000, currency: 'SAR', description: 'Invoice B'),
    new CreateInvoiceDTO(amount: 3500, currency: 'SAR', description: 'Invoice C'),
]);
```
 
### Cancel an Invoice
 
```php
$invoice = $moyasar->invoice()->cancel('invoice_12345');
// $invoice->status === 'canceled'
```
 
### InvoiceDTO Reference
 
| Property | Type | Notes |
| --- | --- | --- |
| `id` | `string` | Unique identifier |
| `status` | `string` | `initiated`, `pending`, `paid`, `expired`, `canceled` |
| `amount` | `int` | Smallest currency unit |
| `currency` | `string` | ISO 4217 |
| `description` | `string` | |
| `url` | `string` | **Hosted payment page — redirect customers here** |
| `amountFormat` | `string` | e.g. `"25.00 SAR"` |
| `logoUrl` | `string` | Your merchant logo |
| `callbackUrl` | `?string` | |
| `successUrl` | `?string` | |
| `backUrl` | `?string` | |
| `expiredAt` | `?string` | |
| `createdAt` | `string` | |
| `updatedAt` | `string` | |
| `metadata` | `array` | |
| `payments` | `array` | Payment attempts linked to this invoice |
 
Need the raw Saloon response? It's always available:
 
```php
$invoice  = $moyasar->invoice()->get('invoice_12345');
$response = $invoice->getResponse();
 
$response->status();   // 200
$response->headers();  // All response headers
$response->body();     // Raw JSON string
```
 
---
 
## Payments
 
Invoices are great when you want Moyasar to host the checkout. **Payments** are for when you want full control — your own card form, your own UX, direct charges against a card or token.
 
> [!NOTE]
> If you're collecting card details directly, make sure your integration is PCI-compliant. For most merchants, **tokenization** (using a saved `token` source) is safer and simpler than passing raw card numbers.
 
### Create a Payment
 
```php
use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\Source\CreditCardSourceDTO;
 
$payment = $moyasar->payment()->create(new CreatePaymentDTO(
    amount:      10000,               // 100.00 SAR
    currency:    'SAR',
    description: 'Kindle Paperwhite',
    source: new CreditCardSourceDTO(
        name:                'John Doe',
        number:              '4111111111111111',
        month:               12,
        year:                2030,
        cvc:                 123,
        statementDescriptor: 'Century Store',
        threeDs:             true,
        manual:              false,
        saveCard:            false,
    ),
    callbackUrl: 'https://example.com/checkout/return',
    metadata:    ['cart_id' => 'cart_abc', 'customer_id' => '23432'],
    givenId:     'a1168bd1-47a4-4b97-8a50-dd5caaccacf2',
    applyCoupon: true,
));
 
echo $payment->status;  // "initiated", "paid", "authorized", ...
echo $payment->id;      // Store this with your order
```
 
**About 3D Secure:** when `threeDs: true`, the response may include a redirect URL the customer must visit to complete verification. Always check `$payment->status` and any redirect instructions returned by the API before assuming the payment succeeded.
 
**About manual capture:** setting `manual: true` authorizes the charge without capturing funds. Use this when you want to verify a payment now but only capture later (e.g. when you ship the item). See [Capture](#capture-an-authorized-payment) below.
 
#### Supported Source Types
 
| Source | DTO | Use case |
| --- | --- | --- |
| Credit/debit card | `CreditCardSourceDTO` | Direct card charge |
| Apple Pay | Pass a raw `array` as `source` | Apple Pay token from the browser/app |
| STC Pay | Pass a raw `array` as `source` | STC Pay flow |
| Saved token | `CreditCardSourceDTO` with `token` set | Charging a previously saved card |
 
### Fetch a Payment
 
```php
$payment = $moyasar->payment()->get('payment_12345');
 
if ($payment->status === 'paid') {
    // Mark the order as paid
}
```
 
### List Payments
 
Same paginator API as invoices:
 
```php
$paginator = $moyasar->payment()->list()->paginate($moyasar);
 
while ($paginator->hasMorePages()) {
    foreach ($paginator->items() as $payment) {
        echo "{$payment->id} — {$payment->status} — {$payment->amountFormat}\n";
    }
 
    $paginator = $paginator->nextPage();
}
```
 
### Update a Payment
 
Update `description` or `metadata` after the fact — handy for enriching records once your internal workflow catches up:
 
```php
use HamodaDev\Moyasar\Payment\DTO\UpdatePaymentDTO;
 
$moyasar->payment()->update('payment_12345', new UpdatePaymentDTO(
    description: 'Kindle Paperwhite — refurbished',
    metadata: [
        'cart_id'        => 'cart_abc',
        'customer_email' => 'john@example.com',
    ],
));
```
 
### Refund a Payment
 
Full refund:
 
```php
$payment = $moyasar->payment()->refund('payment_12345');
```
 
Partial refund (amount in smallest currency unit):
 
```php
$payment = $moyasar->payment()->refund('payment_12345', amount: 2500);  // Refund 25.00 SAR
```
 
### Capture an Authorized Payment
 
If you created the payment with `manual: true`, capture it when you're ready to actually charge the customer:
 
```php
// Full capture
$payment = $moyasar->payment()->capture('payment_12345');
 
// Partial capture (e.g. only ship part of an order)
$payment = $moyasar->payment()->capture('payment_12345', amount: 5000);
```
 
### Void a Payment
 
Cancel a payment **before** the funds settle in your bank account. Works on `paid`, `authorized`, or `captured` payments — as long as settlement hasn't happened yet.
 
```php
$payment = $moyasar->payment()->void('payment_12345');
// $payment->status === 'voided'
```
 
> [!TIP]
> **Void vs. refund:** void *prevents* the money from leaving the customer's account; refund *returns* money that's already moved. Void is cheaper and faster — always prefer it when available.
 
### Payment Status Reference
 
| Status | What it means |
| --- | --- |
| `initiated` | Payment created, customer hasn't paid yet |
| `paid` | Payment succeeded — you can fulfill the order |
| `failed` | Payment failed — check `message` on the DTO for the reason |
| `authorized` | Card authorized but not charged — needs `capture()` |
| `captured` | Authorized payment has been successfully captured |
| `refunded` | Payment refunded (full or partial) |
| `voided` | Payment canceled before settlement |
| `verified` | Card verified during tokenization (no charge made) |
 
---
 
## Error Handling
 
Saloon throws `RequestException` on any non-2xx response. The response object is attached, so you get full context:
 
```php
use Saloon\Exceptions\Request\RequestException;
use Saloon\Exceptions\Request\FatalRequestException;
 
try {
    $payment = $moyasar->payment()->get('invalid_id');
} catch (RequestException $e) {
    $status = $e->getResponse()->status();
    $body   = $e->getResponse()->json();
 
    // Moyasar returns structured errors
    $type    = $body['type']    ?? null;  // e.g. "invalid_request_error"
    $message = $body['message'] ?? null;  // Human-readable summary
    $errors  = $body['errors']  ?? [];    // Field-level validation errors
 
    report($e);
} catch (FatalRequestException $e) {
    // Network failure — didn't even reach Moyasar
    report($e);
}
```
 
### Moyasar Error Types
 
| Type | Meaning | What to do |
| --- | --- | --- |
| `invalid_request_error` | You sent bad parameters | Check the `errors` field, fix, retry |
| `authentication_error` | Invalid API key | Verify your secret key and base URL |
| `rate_limit_error` | Too many requests | Back off and retry with exponential delay |
| `api_connection_error` | Couldn't reach Moyasar | Retry with backoff |
| `account_inactive_error` | Account not activated for live payments | Contact Moyasar sales |
| `3ds_auth_error` | 3D Secure failed | Ask the customer to try again |
| `api_error` | Something else went wrong | Retry; contact support if it persists |
 
### HTTP Status Codes
 
| Code | Meaning |
| --- | --- |
| `200` | Success |
| `400` | Bad request — missing or invalid parameters |
| `401` | Unauthorized — API key invalid |
| `403` | Forbidden — credentials lack permission |
| `404` | Resource not found |
| `405` | Method not allowed — account not activated for live |
| `429` | Rate limited |
| `500` / `503` | Moyasar server issue — retry later |
 
> [!WARNING]
> Moyasar occasionally returns **`201` with a failure payload** (e.g. bank declines). Don't trust the status code alone — always inspect `$payment->status` or `$invoice->status` on the DTO.
 
---
 
## Testing Your Integration
 
Use Moyasar's [test cards](https://docs.moyasar.com/testing) with your `sk_test_...` key. A few quick patterns:
 
| Scenario | Card number |
| --- | --- |
| Successful charge | `4111 1111 1111 1111` |
| Declined charge | `4000 0000 0000 0002` |
| 3D Secure required | `4000 0000 0000 3220` |
 
Always verify your webhook handler works end-to-end in `test` mode before flipping to live.
 
---
 
## Contributing
 
Bug reports and pull requests welcome. If you're adding a new Moyasar endpoint, please follow the existing Resource / Request / DTO pattern — consistency is why this SDK is pleasant to use.
 
## License
 
Moyasar PHP is open-sourced software licensed under the [MIT license](LICENSE).