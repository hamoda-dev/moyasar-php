<?php

namespace HamodaDev\Moyasar\Payment;

use HamodaDev\Moyasar\Payment\APIs\CapturePaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\CreatePaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\GetPaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\ListPaymentsRequest;
use HamodaDev\Moyasar\Payment\APIs\RefundPaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\UpdatePaymentRequest;
use HamodaDev\Moyasar\Payment\APIs\VoidPaymentRequest;
use HamodaDev\Moyasar\Payment\DTO\CreatePaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\PaymentDTO;
use HamodaDev\Moyasar\Payment\DTO\UpdatePaymentDTO;
use Saloon\Http\BaseResource;

class PaymentResource extends BaseResource
{
    public function get(string $paymentId): PaymentDTO
    {
        return $this->connector->send(new GetPaymentRequest($paymentId))->dto();
    }

    public function list(): ListPaymentsRequest
    {
        return new ListPaymentsRequest;
    }

    public function create(CreatePaymentDTO $dto): PaymentDTO
    {
        return $this->connector->send(new CreatePaymentRequest($dto))->dto();
    }

    public function update(string $paymentId, UpdatePaymentDTO $dto): PaymentDTO
    {
        return $this->connector->send(new UpdatePaymentRequest($paymentId, $dto))->dto();
    }

    public function refund(string $paymentId, ?int $amount = null): PaymentDTO
    {
        return $this->connector->send(new RefundPaymentRequest($paymentId, $amount))->dto();
    }

    public function capture(string $paymentId, ?int $amount = null): PaymentDTO
    {
        return $this->connector->send(new CapturePaymentRequest($paymentId, $amount))->dto();
    }

    public function void(string $paymentId): PaymentDTO
    {
        return $this->connector->send(new VoidPaymentRequest($paymentId))->dto();
    }
}
