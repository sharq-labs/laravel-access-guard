<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;

class ShowWhitelistedIpsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-guard:show-whitelisted-ips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all whitelisted IPs from the user_access_records table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $whitelistedIps = UserAccessRecord::where('is_whitelisted', true)
            ->get(['id', 'email', 'primary_ip', 'created_at', 'updated_at']);

        if ($whitelistedIps->isEmpty()) {
            $this->info('No whitelisted IPs found.');
            return 0;
        }

        $this->info('Whitelisted IPs:');
        $this->table(
            ['ID', 'Email', 'Domain','Primary IP', 'Created At', 'Updated At'],
            $whitelistedIps->map(function ($record) {
                return [
                    'ID' => $record->id,
                    'Email' => $record->email ?? 'N/A',
                    'Domain' => $record->domain ?? 'N/A',
                    'Primary IP' => $record->primary_ip ?? 'N/A',
                    'Created At' => $record->created_at,
                    'Updated At' => $record->updated_at,
                ];
            })->toArray()
        );

        return 0;
    }
}
