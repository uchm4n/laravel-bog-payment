<?php

namespace Jorjika\BogPayment\Traits;

trait HandlesSubscription
{
    public function subscribe(): array
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->apiClient->put("/orders/{$response['id']}/subscriptions");
        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    public function chargeSubscription($subscriptionId): array
    {
        $response = $this->apiClient->post("/ecommerce/orders/{$subscriptionId}/subscribe", $this->payload);

        return [
            'id' => $response['id'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }
}
