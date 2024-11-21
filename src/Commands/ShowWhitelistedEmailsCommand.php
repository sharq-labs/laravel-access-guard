<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Exception;

class ShowWhitelistedEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-guard:show-whitelisted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all whitelisted Emails from the user_access_records table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $whitelisted = $this->getWhitelisted();

            if ($whitelisted->isEmpty()) {
                $this->info('No whitelisted Emails found.');
                return 0;
            }

            $this->info('Whitelisted Emails:');
            $this->table(
                ['ID', 'Email', 'Domain', 'Created At', 'Updated At'],
                $whitelisted->map(function (UserAccessRecord $record) {
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
            $this->error('An error occurred while fetching whitelisted Emails: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get the list of whitelisted Emails.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getWhitelisted()
    {
        return UserAccessRecord::query()
            ->whereHas('browsers', function ($query) {
                $query->where('expires_at', '>', now());
            })
            ->get();
    }
}
