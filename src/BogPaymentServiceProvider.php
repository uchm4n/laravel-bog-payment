<?php

namespace RedberryProducts\LaravelBogPayment;

use RedberryProducts\LaravelBogPayment\Commands\BogPaymentCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BogPaymentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('bog-payment')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasMigration('create_bog_payment_table')
            ->hasCommand(BogPaymentCommand::class);
    }
}
