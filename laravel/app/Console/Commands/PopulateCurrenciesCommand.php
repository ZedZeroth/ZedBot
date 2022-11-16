<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Currencies\CurrencyPopulator;
use App\Models\Currency;

class PopulateCurrenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public /* Do not define */ $signature =
        'currencies:populate {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected /* Do not define */ $description =
        'Creates all required currencies.';

    /**
     * Execute the command via the CommandInformer.
     *
     */
    public function handle(): void
    {
        try {
            (new CommandInformer())->run(command: $this);
        } catch (Exception $e) {
            $this->error(__METHOD__ . ' [' . __LINE__ . ']');
            Log::error(__METHOD__ . ' [' . __LINE__ . ']');
        }
    }

    /**
     * Execute the command itself.
     *
     * @return CurrencyPopulator
     */
    public function runThisCommand(): CurrencyPopulator
    {
        return (new CurrencyController())->populate();
    }
}
