<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Currencies\CurrencyPopulator;

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
        (new CommandInformer())->run(command: $this);
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
