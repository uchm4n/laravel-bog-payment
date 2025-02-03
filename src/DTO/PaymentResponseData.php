<?php

namespace RedberryProducts\LaravelBogPayment\DTO;

use ArrayAccess;
use RedberryProducts\LaravelBogPayment\Traits\Utils\Arrayable;

final class PaymentResponseData implements ArrayAccess
{
    use Arrayable;

    private mixed $id;

    private ?string $redirect_url;

    private ?string $details_url;

    public function __construct($id, $redirect_url = null, $details_url = null)
    {
        $this->id = $id;
        $this->redirect_url = $redirect_url;
        $this->details_url = $details_url;
    }
}
