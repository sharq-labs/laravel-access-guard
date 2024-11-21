<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;

class AddAccessRecordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-guard:add-record
                            {--email= : The email address to add}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an email or IP address to the user_access_records table with optional whitelist status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');

        if (!$email) {
            $this->error('You must provide at least an email address.');
            return 1;
        }

        // Create or update the record
        $record =  UserAccessRecord::updateOrCreate(
        ['email' => $email],
        ['domain' => AccessGuardService::getDomainFromEmail($email)]
    );


        $this->info('Access record added successfully:');
        $this->table(
            ['ID', 'Email', 'Domain', 'Created At', 'Updated At'],
            [[
                'ID' => $record->id,
                'Email' => $record->email ?? 'N/A',
                'Domain' => $record->domain ?? 'N/A',
                'Created At' => $record->created_at,
                'Updated At' => $record->updated_at,
            ]]
        );

        return 0;
    }
}
