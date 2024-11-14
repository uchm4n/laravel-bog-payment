<?php

namespace Jorjika\BogPayment;

use Jorjika\BogPayment\Contracts\PayContract;
use Jorjika\BogPayment\Traits\BuildsPayment;

class Pay implements PayContract
{
    use BuildsPayment;

    public ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        $this->resetPayload();
    }

    public function process(): array
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        if ($this->saveCard) {
            $this->registerCard($response['id']);
        }

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    private function registerCard(mixed $id)
    {
        $this->apiClient->put("/orders/{$id}/cards");
    }
}
