<?php

namespace Jorjika\BogPayment;

use Jorjika\BogPayment\Contracts\PaymentGatewayInterface;

class BogPayment implements PaymentGatewayInterface
{
    public function __construct(public readonly ApiClient $apiClient) {}

    public function init() {}

    public function createPayment(array $data)
    {
        // Implementation to initiate payment
    }

    public function handleCallback(array $data)
    {
        // Handle payment callback
    }

    public function refundPayment(array $data)
    {
        // Refund logic
    }
}
