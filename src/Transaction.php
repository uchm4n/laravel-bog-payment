<?php

namespace RedberryProducts\LaravelBogPayment;

use RedberryProducts\LaravelBogPayment\Contracts\TransactionContract;

class Transaction implements TransactionContract
{
    public ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function get($order_id): array
    {
        return $this->apiClient->get("/receipt/{$order_id}");
    }
}
