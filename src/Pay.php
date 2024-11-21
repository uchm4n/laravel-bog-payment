<?php

namespace Jorjika\BogPayment;

use Exception;
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

    /**
     *  Charge payment method with given payment method id
     *
     * @throws Exception
     */
    public function chargeCard($paymentMethodId): array
    {
        if (! $paymentMethodId) {
            throw new Exception('Payment method id is required');
        }

        $response = $this->apiClient->post("/ecommerce/orders/$paymentMethodId", $this->payload);

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
