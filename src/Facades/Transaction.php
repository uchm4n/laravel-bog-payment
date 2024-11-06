<?php

namespace Jorjika\BogPayment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jorjika\BogPayment\BogPayment
 */
class Transaction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jorjika\BogPayment\Transaction::class;
    }
}
