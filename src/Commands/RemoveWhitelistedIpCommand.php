<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;

class RemoveWhitelistedIpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-guard:remove-whitelisted
                            {--email= : The email address to remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a whitelisted email from the user_access_records table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');

        if (!$email) {
            $this->error('You must provide at least an email address to remove.');
            return 1;
        }

        $record = UserAccessRecord::query()->where('email', $email)->first();

        if (!$record) {
            $this->error('No matching whitelisted record found.');
            return 1;
        }

        $record->clearBrowserExpiry();

        $this->info('Whitelisted record removed successfully:');
        $this->table(
            ['ID', 'Email', 'Domain'],
            [[
                'ID' => $record->id,
                'Email' => $record->email ?? 'N/A',
                'Domain' => $record->domain ?? 'N/A',
            ]]
        );

        return 0;
    }
}
