<?php

namespace RedberryProducts\LaravelBogPayment;

use RedberryProducts\LaravelBogPayment\Traits\BuildsPayment;

class Payment
{
    use BuildsPayment;

    public ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->resetPayload();
    }
}
