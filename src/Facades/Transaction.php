<?php

namespace RedberryProducts\LaravelBogPayment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RedberryProducts\LaravelBogPayment\BogPayment
 */
class Transaction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RedberryProducts\LaravelBogPayment\Transaction::class;
    }
}
