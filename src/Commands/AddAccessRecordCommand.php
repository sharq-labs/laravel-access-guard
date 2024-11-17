<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;

class AddAccessRecordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-guard:add-record
                            {--email= : The email address to add}
                            {--ip= : The IP address to add}
                            {--is-whitelisted : Mark this record as whitelisted}';

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
        $ip = $this->option('ip');
        $isWhitelisted = $this->option('is-whitelisted');

        if (!$email && !$ip) {
            $this->error('You must provide at least an email or IP address.');
            return 1;
        }

        // Create or update the record
        $record = UserAccessRecord::updateOrCreate(
            ['email' => $email],
            [
                'primary_ip' => $ip,
                'is_whitelisted' => $isWhitelisted,
            ]
        );

        $this->info('Access record added successfully:');
        $this->table(
            ['ID', 'Email', 'Primary IP', 'Is Whitelisted', 'Created At', 'Updated At'],
            [[
                'ID' => $record->id,
                'Email' => $record->email ?? 'N/A',
                'Primary IP' => $record->primary_ip ?? 'N/A',
                'Is Whitelisted' => $record->is_whitelisted ? 'Yes' : 'No',
                'Created At' => $record->created_at,
                'Updated At' => $record->updated_at,
            ]]
        );

        return 0;
    }
}
