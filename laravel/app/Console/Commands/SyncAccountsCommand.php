<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AccountController;

class SyncAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'accounts:sync {source} {Provider} {Number to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Synchronizes the account table with accounts from account providers.';

    /**
     * Executes the command via the
     * ExceptionCatcher and CommandInformer.
     *
     */
    public function handle(): void
    {
        (new ExceptionCatcher())->catch(
            command: $this,
            class: __CLASS__,
            function: __FUNCTION__,
            line: __LINE__
        );
    }

    /**
     *
     * Execute the command itself.
     *
     */
    public function runThisCommand(): void
    {
        // Build the DTO
        $commandDTO = new SyncCommandDTO(
            provider: $this->argument('Provider'),
            numberToFetch: (int) $this->argument('Number to fetch')
        );

        // Inject the DTO into the relevant controller method
        (new AccountController())
            ->sync(commandDTO: $commandDTO);

        return;
    }
}
