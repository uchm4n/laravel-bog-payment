<?php

namespace Jorjika\BogPayment\Builders;

class PaymentPayloadBuilder
{
    private array $payload = [];

    public function setCallbackUrl(string $callbackUrl): self
    {
        $this->payload['callback_url'] = $callbackUrl;
        return $this;
    }

    public function setExternalOrderId(string $externalOrderId): self
    {
        $this->payload['external_order_id'] = $externalOrderId;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->payload['purchase_units']['currency'] = $currency;
        return $this;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->payload['purchase_units']['total_amount'] = $totalAmount;
        return $this;
    }

    public function setBasket(array $basket): self
    {
        $this->payload['purchase_units']['basket'] = $basket;
        return $this;
    }

    public function setRedirectUrls(string $failUrl, string $successUrl): self
    {
        $this->payload['redirect_urls'] = [
            'fail' => $failUrl,
            'success' => $successUrl,
        ];
        return $this;
    }

    public function build(): array
    {
        return $this->payload;
    }
}
