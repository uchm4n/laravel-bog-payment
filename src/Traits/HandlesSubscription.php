<?php

namespace RedberryProducts\LaravelBogPayment\Traits;

use RedberryProducts\LaravelBogPayment\DTO\PaymentResponseData;

trait HandlesSubscription
{
    public function subscribe(): PaymentResponseData
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->apiClient->put("/orders/{$response['id']}/subscriptions");
        $this->resetPayload();

        return new PaymentResponseData(
            id: $response['id'],
            redirect_url: $response['_links']['redirect']['href'],
            details_url: $response['_links']['details']['href']
        );
    }

    public function chargeSubscription($subscriptionId): PaymentResponseData
    {
        $response = $this->apiClient->post("/ecommerce/orders/{$subscriptionId}/subscribe", $this->payload);

        return new PaymentResponseData(
            id: $response['id'],
            details_url: $response['_links']['details']['href'],
        );
    }
}
