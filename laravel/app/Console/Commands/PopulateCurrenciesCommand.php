<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CurrencyController;

class PopulateCurrenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates all required currencies.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /* Run the CurrencyController 'populate' method */
        (new CurrencyController())->populate();

        /* Output messages */
        $output = 'Currencies populated.';
        $this->info($output);
        Log::info($output);
    }
}
