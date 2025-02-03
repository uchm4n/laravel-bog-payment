<?php

namespace RedberryProducts\LaravelBogPayment;

use RedberryProducts\LaravelBogPayment\Contracts\PayContract;
use RedberryProducts\LaravelBogPayment\DTO\PaymentResponseData;

class Pay extends Payment implements PayContract
{
    use Traits\HandlesCard,
        Traits\HandlesSubscription;

    public function process(): PaymentResponseData
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->resetPayload();

        return new PaymentResponseData(
            id: $response['id'],
            redirect_url: $response['_links']['redirect']['href'],
           details_url:  $response['_links']['details']['href']
        );
    }
}
