<?php

namespace Jorjika\BogPayment\Commands;

use Illuminate\Console\Command;

class BogPaymentCommand extends Command
{
    public $signature = 'bog-payment';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
