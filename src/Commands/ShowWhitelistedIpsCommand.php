<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Exception;

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
    public function handle(): int
    {
        try {
            $whitelistedIps = $this->getWhitelistedIps();

            if ($whitelistedIps->isEmpty()) {
                $this->info('No whitelisted IPs found.');
                return 0;
            }

            $this->info('Whitelisted IPs:');
            $this->table(
                ['ID', 'Email', 'Domain', 'Created At', 'Updated At'],
                $whitelistedIps->map(function (UserAccessRecord $record) {
                    return [
                        'ID' => $record->id,
                        'Email' => $record->email ?? 'N/A',
                        'Domain' => $record->domain ?? 'N/A',
                        'Created At' => $record->created_at->toDateTimeString(),
                        'Updated At' => $record->updated_at->toDateTimeString(),
                    ];
                })->toArray()
            );

            return 0;
        } catch (Exception $e) {
            $this->error('An error occurred while fetching whitelisted IPs: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get the list of whitelisted IPs.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getWhitelistedIps()
    {
        return UserAccessRecord::query()
            ->whereHas('browsers', function ($query) {
                $query->where('expires_at', '>', now());
            })
            ->get();
    }
}
