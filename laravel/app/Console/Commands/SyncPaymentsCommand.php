<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PaymentController;
use Exception;

class SyncPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected /* Do not define */ $signature =
        'payments:sync {source} {Provider} {Number to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Synchronizes the payment table with new payments from payment providers.';

    /**
     * Execute the command via the CommandInformer.
     *
     */
    public function handle(): void
    {
        (new CommandInformer())->run(command: $this);
    }

    /**
     * Execute the command itself.
     *
     */
    public function runThisCommand(): void
    {
        // Build the DTO
        $dto = new CommandDTO(
            data: [
                'provider'
                    => $this->argument('Provider'),
                'numberOfPaymentsToFetch'
                    => $this->argument('Number to fetch')
            ]
        );

        // Inject the DTO into the relevant controller method
        (new PaymentController())
            ->sync($dto);

        return;
    }
}
