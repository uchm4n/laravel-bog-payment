<?php

namespace Nikajorjika\BogPayment\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TransactionStatusUpdated
{
    use Dispatchable, SerializesModels;

    public array $transaction;

    public function __construct(array $transaction)
    {
        $this->transaction = $transaction;
    }
}
