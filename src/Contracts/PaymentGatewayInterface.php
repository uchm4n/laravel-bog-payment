<?php

namespace RedberryProducts\LaravelBogPayment\Contracts;

interface PaymentGatewayInterface
{
    public function createPayment(array $data);

    public function handleCallback(array $data);

    public function refundPayment(array $data);
}
