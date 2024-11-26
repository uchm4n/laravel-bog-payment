<?php

namespace Jorjika\BogPayment;

use Jorjika\BogPayment\Traits\BuildsPayment;

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
