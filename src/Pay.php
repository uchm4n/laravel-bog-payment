<?php

namespace RedberryProducts\LaravelBogPayment;

use RedberryProducts\LaravelBogPayment\Contracts\PayContract;

class Pay extends Payment implements PayContract
{
    use Traits\HandlesCard,
        Traits\HandlesSubscription;

    public function process(): array
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }
}
