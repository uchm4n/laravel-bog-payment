<?php

namespace RedberryProducts\LaravelBogPayment\Traits;

use Exception;
use RedberryProducts\LaravelBogPayment\DTO\PaymentResponseData;

trait HandlesCard
{
    public function saveCard(): PaymentResponseData
    {
        $response = $this->apiClient->post('/ecommerce/orders', $this->payload);

        $this->apiClient->put("/orders/{$response['id']}/cards");

        $this->resetPayload();

        return new PaymentResponseData(
            id: $response['id'],
            redirect_url: $response['_links']['redirect']['href'],
            details_url: $response['_links']['details']['href'],
        );
    }

    /**
     * @throws Exception
     */
    public function chargeCard($parentTransactionId): PaymentResponseData
    {
        if (!$parentTransactionId) {
            throw new Exception('Payment method id is required');
        }

        $response = $this->apiClient->post("/ecommerce/orders/$parentTransactionId", $this->payload);

        $this->resetPayload();

        return new PaymentResponseData(
            id: $response['id'],
            redirect_url: $response['_links']['redirect']['href'],
            details_url: $response['_links']['details']['href'],
        );
    }

    public function deleteCard(mixed $id): void
    {
        $this->apiClient->delete("/charges/card/{$id}");
    }
}
