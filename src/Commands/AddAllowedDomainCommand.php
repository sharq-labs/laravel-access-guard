<?php

namespace Sharqlabs\LaravelAccessGuard\Commands;

use Illuminate\Console\Command;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessAllowedDomain;

class AddAllowedDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-guard:add-domain
                            {--domain= : The domain to add}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a domain to the allowed_domains table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domain = $this->option('domain');

        if (!$domain) {
            $this->error('You must provide a domain.');
            return 1;
        }

        // Create or find the domain
        $record = UserAccessAllowedDomain::firstOrCreate(
            ['domain' => $domain]
        );

        $this->info('Domain added successfully:');
        $this->table(
            ['ID', 'Domain', 'Created At', 'Updated At'],
            [[
                'ID' => $record->id,
                'Domain' => $record->domain,
                'Created At' => $record->created_at,
                'Updated At' => $record->updated_at,
            ]]
        );

        return 0;
    }
}
