<?php

namespace Nikajorjika\BogPayment\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionStatusUpdated
{
    use Dispatchable, SerializesModels;

    public array $transaction;

    public function __construct(array $transaction)
    {
        $this->transaction = $transaction;
    }
}
