<?php

namespace RedberryProducts\LaravelBogPayment\Traits;

use Exception;

trait HandlesCard
{
    public function saveCard(): array
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->apiClient->put("/orders/{$response['id']}/cards");

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'redirect_url' => $response['_links']['redirect']['href'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    /**
     * @throws Exception
     */
    public function chargeCard($parentTransactionId): array
    {
        if (! $parentTransactionId) {
            throw new Exception('Payment method id is required');
        }

        $response = $this->apiClient->post("/ecommerce/orders/$parentTransactionId", $this->payload);

        $this->resetPayload();

        return [
            'id' => $response['id'],
            'details_url' => $response['_links']['details']['href'],
        ];
    }

    public function deleteCard(mixed $id): void
    {
        $this->apiClient->delete("/charges/card/{$id}");
    }
}
