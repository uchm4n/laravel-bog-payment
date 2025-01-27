<?php

namespace RedberryProducts\LaravelBogPayment\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use RedberryProducts\LaravelBogPayment\BogPaymentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'RedberryProducts\\LaravelBogPayment\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            BogPaymentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_bog-payment_table.php.stub';
        $migration->up();
        */
    }
}
