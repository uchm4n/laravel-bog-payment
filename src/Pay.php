<?php

namespace Jorjika\BogPayment;

use Jorjika\BogPayment\Contracts\PayContract;

class Pay extends Payment implements PayContract
{
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
