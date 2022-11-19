<?php

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
     * Execute the command via the CommandInformer.
     *
     */
    public function handle(): void
    {
        try {
            try {
                try {
                    (new CommandInformer())
                        ->run(command: $this);
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    (new ExceptionInformer())->warn(
                        command: $this,
                        e: $e,
                        class: __CLASS__,
                        function: __FUNCTION__,
                        line: __LINE__
                    );
                }
            } catch (\Illuminate\Http\Client\RequestException $e) {
                (new ExceptionInformer())->warn(
                    command: $this,
                    e: $e,
                    class: __CLASS__,
                    function: __FUNCTION__,
                    line: __LINE__
                );
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            (new ExceptionInformer())->warn(
                command: $this,
                e: $e,
                class: __CLASS__,
                function: __FUNCTION__,
                line: __LINE__
            );
        }
    }

    /**
     * Execute the command itself.
     *
     */
    public function runThisCommand(): void
    {
        // Build the DTO
        $commandDTO = new CommandDTO(
            data: [
                'provider'
                    => $this->argument('Provider'),
                'numberOfAccountsToFetch'
                    => $this->argument('Number to fetch')
            ]
        );

        // Inject the DTO into the relevant controller method
        (new AccountController())
            ->sync(commandDTO: $commandDTO);

        return;
    }
}
