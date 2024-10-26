<?php

namespace Jorjika\BogPayment;

use Jorjika\BogPayment\Contracts\PayInterface;
use Jorjika\BogPayment\Traits\BuildsPayment;

class Pay implements PayInterface
{
    use BuildsPayment;

    public function __construct(public readonly ApiClient $apiClient)
    {
        $this->initPayload();
    }

    public function process(): array
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }
}
