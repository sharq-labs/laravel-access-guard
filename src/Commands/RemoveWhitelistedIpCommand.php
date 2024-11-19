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
                            {--email= : The email address to remove}
                            {--ip= : The IP address to remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a whitelisted IP or email from the user_access_records table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $ip = $this->option('ip');

        if (!$email && !$ip) {
            $this->error('You must provide at least an email or IP address to remove.');
            return 1;
        }

        $query = UserAccessRecord::query();

        if ($email) {
            $query->where('email', $email);
        }

        if ($ip) {
            $query->where('primary_ip', $ip);
        }

        $record = $query->where('is_whitelisted', true)->first();

        if (!$record) {
            $this->error('No matching whitelisted record found.');
            return 1;
        }

        $record->delete();

        $this->info('Whitelisted record removed successfully:');
        $this->table(
            ['ID', 'Email', 'Domain', 'Primary IP'],
            [[
                'ID' => $record->id,
                'Email' => $record->email ?? 'N/A',
                'Domain' => $record->domain ?? 'N/A',
                'Primary IP' => $record->primary_ip ?? 'N/A',
            ]]
        );

        return 0;
    }
}
