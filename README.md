# Moyasar PHP

Moyasar PHP provides a type-safe PHP interface to the [Moyasar](https://moyasar.com) payment gateway, built on [Saloon](https://saloon.dev). It currently supports the full **Invoice API**.

## Requirements

- PHP 8.2+
- [Saloon](https://docs.saloon.dev) ^4.0
- [Saloon Pagination Plugin](https://docs.saloon.dev/docs/the-pagination-plugin) ^2.3

## Installation

Install the package via Composer:

```bash
composer require hamoda-dev/moyasar-php
```

## Configuration

Instantiate the `Moyasar` connector with your API key and the desired base URL:

```php
use HamodaDev\Moyasar\Moyasar;

$moyasar = new Moyasar(
    baseUrl: 'https://api.moyasar.com/v1',
    apiKey: config('services.moyasar.secret_key'),
);
```

You may store your credentials in your environment file:

```env
MOYASAR_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxx
MOYASAR_BASE_URL=https://api.moyasar.com/v1
```

> [!WARNING]
> Never commit your Moyasar API key to version control. Always use environment variables or a secrets manager.

## Usage

### Creating an Invoice

To create a new invoice, pass a `CreateInvoiceDTO` to the `CreateInvoiceRequest`:

```php
use HamodaDev\Moyasar\Invoice\APIs\CreateInvoiceRequest;
use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;

$dto = new CreateInvoiceDTO(
    amount: 2500,
    currency: 'SAR',
    description: 'Order #1234',
    callbackUrl: 'https://example.com/webhooks/moyasar',
    successUrl: 'https://example.com/payment/success',
    backUrl: 'https://example.com/payment/cancel',
    metadata: ['order_id' => '1234'],
);

$response = $moyasar->send(new CreateInvoiceRequest($dto));

$invoice = $response->dto();
echo $invoice->url; // The hosted invoice payment page
```

| Parameter | Type | Required | Description |
| --- | --- | --- | --- |
| `amount` | `int` | Yes | Amount in the smallest currency unit (e.g. halalas for SAR) |
| `currency` | `string` | Yes | Three-letter ISO currency code (e.g. `SAR`) |
| `description` | `string` | Yes | A description of the invoice |
| `callbackUrl` | `string\|null` | No | URL that Moyasar will call when the payment status changes |
| `successUrl` | `string\|null` | No | URL to redirect the customer to after a successful payment |
| `backUrl` | `string\|null` | No | URL to redirect the customer to if they cancel |
| `expiredAt` | `string\|null` | No | ISO 8601 datetime after which the invoice expires |
| `metadata` | `array\|null` | No | Key-value pairs attached to the invoice |

You may also create a DTO from an array:

```php
$dto = CreateInvoiceDTO::fromArray([
    'amount' => 2500,
    'currency' => 'SAR',
    'description' => 'Order #1234',
    'metadata' => ['order_id' => '1234'],
]);
```

### Retrieving an Invoice

To retrieve a single invoice by its ID, use the `GetInvoiceRequest`:

```php
use HamodaDev\Moyasar\Invoice\APIs\GetInvoiceRequest;

$response = $moyasar->send(new GetInvoiceRequest(
    invoiceId: 'invoice_12345',
));

$invoice = $response->dto();
echo $invoice->status; // e.g. "paid", "pending", "expired"
```

### Listing Invoices

To list all invoices with pagination, use the `ListInvoicesRequest`:

```php
use HamodaDev\Moyasar\Invoice\APIs\ListInvoicesRequest;

$request = new ListInvoicesRequest;
$paginator = $request->paginate($moyasar);

// Iterate through all pages
while ($paginator->hasMorePages()) {
    foreach ($paginator->items() as $invoice) {
        echo $invoice->id . ' - ' . $invoice->status;
    }

    $paginator = $paginator->nextPage();
}
```

> [!TIP]
> Each item yielded by `items()` is an `InvoiceDTO` instance, giving you type-safe access to all invoice properties.

### Updating an Invoice

To update an invoice's metadata, use the `UpdateInvoiceRequest` with an `UpdateInvoiceDTO`:

```php
use HamodaDev\Moyasar\Invoice\APIs\UpdateInvoiceRequest;
use HamodaDev\Moyasar\Invoice\DTO\UpdateInvoiceDTO;

$response = $moyasar->send(new UpdateInvoiceRequest(
    invoiceId: 'invoice_12345',
    updateInvoiceDTO: new UpdateInvoiceDTO(
        metadata: ['order_id' => '1234', 'customer_note' => 'Rush delivery'],
    ),
));

$invoice = $response->dto();
```

### Bulk Creating Invoices

To create multiple invoices in a single request, pass an array of `CreateInvoiceDTO` instances to the `BulkCreateInvoicesRequest`:

```php
use HamodaDev\Moyasar\Invoice\APIs\BulkCreateInvoicesRequest;
use HamodaDev\Moyasar\Invoice\DTO\CreateInvoiceDTO;

$response = $moyasar->send(new BulkCreateInvoicesRequest(
    invoices: [
        new CreateInvoiceDTO(amount: 1000, currency: 'SAR', description: 'Invoice A'),
        new CreateInvoiceDTO(amount: 2000, currency: 'SAR', description: 'Invoice B'),
    ],
));
```

### Canceling an Invoice

To cancel an invoice, use the `CancelInvoiceRequest`:

```php
use HamodaDev\Moyasar\Invoice\APIs\CancelInvoiceRequest;

$response = $moyasar->send(new CancelInvoiceRequest(
    invoiceId: 'invoice_12345',
));

$invoice = $response->dto();
echo $invoice->status; // "canceled"
```

## Response DTO

All requests that return a single invoice resolve to an `InvoiceDTO`. You can access it via `$response->dto()`:

```php
$response = $moyasar->send(new GetInvoiceRequest(invoiceId: 'invoice_12345'));
$invoice = $response->dto();
```

### InvoiceDTO Properties

| Property | Type | Description |
| --- | --- | --- |
| `id` | `string` | Unique invoice identifier |
| `status` | `string` | Current status (`init`, `pending`, `paid`, `expired`, `canceled`) |
| `amount` | `int` | Amount in the smallest currency unit |
| `currency` | `string` | Three-letter ISO currency code |
| `description` | `string` | Invoice description |
| `logoUrl` | `string` | Merchant logo URL |
| `amountFormat` | `string` | Human-readable amount string |
| `url` | `string` | Hosted invoice payment page URL |
| `callbackUrl` | `string\|null` | Webhook callback URL |
| `expiredAt` | `string\|null` | Expiration timestamp |
| `createdAt` | `string` | Creation timestamp |
| `updatedAt` | `string` | Last update timestamp |
| `backUrl` | `string\|null` | Cancel redirect URL |
| `successUrl` | `string\|null` | Success redirect URL |
| `metadata` | `array` | Key-value metadata attached to the invoice |
| `payments` | `array` | Associated payment records |

To access the underlying Saloon response, use the `getResponse()` method provided by the `HasResponse` trait:

```php
$invoice = $response->dto();
$rawResponse = $invoice->getResponse();
$statusCode = $rawResponse->status();
```

## Error Handling

The package relies on Saloon's exception handling. You may catch exceptions to handle API errors gracefully:

```php
use Saloon\Exceptions\Request\RequestException;

try {
    $response = $moyasar->send(new GetInvoiceRequest(invoiceId: 'invalid_id'));
    $invoice = $response->dto();
} catch (RequestException $exception) {
    $statusCode = $exception->getResponse()->status();
    $body = $exception->getResponse()->body();

    // Handle the error (4xx client errors, 5xx server errors)
}
```

> [!NOTE]
> Saloon throws `RequestException` for all HTTP errors. You may catch `FourRequestException` for client errors (4xx) and `FiveRequestException` for server errors (5xx) separately if you need to handle them differently.

## License

Moyasar PHP is open-sourced software licensed under the [MIT license](LICENSE).
