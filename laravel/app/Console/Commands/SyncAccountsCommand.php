<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AccountController;
use App\Models\Account;

class SyncAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'accounts:sync {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Synchronizes the account table with accounts from account providers.';

    /**
     * Initial output message
     *
     * @param int $numberOfAccountsToFetch
     * @param int $initialAccounts
     */
    public function start(
        int $numberOfAccountsToFetch,
        int $initialAccounts
    ): void {
        $outputs = [
            'Current number of accounts:         ' . $initialAccounts,
            'Number of recent accounts to fetch: ' . $numberOfAccountsToFetch,
            '... Fetching ...',
        ];

        $formattedOutputs = (new OutputFormatter())->format(
            commandName: $this->signature,
            startOrEnd: 'start',
            textArray: $outputs
        );

        foreach ($formattedOutputs as $output) {
            $this->info($output);
            Log::info($output);
        }
    }

    /**
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        $numberOfAccountsToFetch = $this->argument('number');
        $initialAccounts = Account::all()->count();

        // Initialize
        $this->start(
            numberOfAccountsToFetch: $numberOfAccountsToFetch,
            initialAccounts: $initialAccounts,
        );

        // Run the commanded action
        $accountsFetched = (new AccountController())
            ->sync(
                provider: 'LCS',
                numberOfAccounts: $numberOfAccountsToFetch
            );

        // Finalize
        $this->finish(
            numberOfAccountsToFetch: $numberOfAccountsToFetch,
            initialAccounts: $initialAccounts,
            accountsFetched: $accountsFetched,
        );
    }

    /**
     * Final output message
     *
     * @param int $numberOfAccountsToFetch
     * @param int $initialAccounts
     * @param array $accountsFetched
     */
    public function finish(
        int $numberOfAccountsToFetch,
        int $initialAccounts,
        array $accountsFetched
    ): void {
        $numberOfAccountsToFetch = count($accountsFetched);
        $finalAccounts = Account::all()->count();

        $outputs = [
            '... [ DONE ] ...',
            'Accounts successfully fetched: ' . $numberOfAccountsToFetch,
            'New total number of accounts:  ' . $finalAccounts,
            'New accounts created:          ' . ($finalAccounts - $initialAccounts),
        ];

        $formattedOutputs = (new OutputFormatter())->format(
            commandName: $this->signature,
            startOrEnd: 'end',
            textArray: $outputs
        );

        foreach ($formattedOutputs as $output) {
            if ($numberOfAccountsToFetch) {
                $this->info($output);
                Log::info($output);
            } else {
                $this->warn($output);
                Log::warning($output);
            }
        }
    }
}
