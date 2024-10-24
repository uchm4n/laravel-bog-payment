<?php

namespace Jorjika\BogPayment;

use Jorjika\BogPayment\Contracts\PaymentGatewayInterface;
use Jorjika\BogPayment\Services\ApiClient;

class BogPayment implements PaymentGatewayInterface
{
    public function __construct(public readonly ApiClient $apiClient)
    {
    }

    public function createPayment(array $data)
    {
        $this->apiClient;
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
