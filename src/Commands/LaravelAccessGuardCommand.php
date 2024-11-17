<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;

class LaravelAccessGuardCommand extends Command
{
    public $signature = 'laravel-access-guard';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
