<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CurrencyController;
use App\Models\Currency;

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
        /* Initial output messages */
        $output = 'Populating currencies ...';
        $this->info($output);
        Log::info($output);

        /* Run the CurrencyController 'populate' method */
        (new CurrencyController())->populate();

        /* Final output messages */
        $output = '... ' . Currency::all()->count() . ' currencies populated.';
        $this->info($output);
        Log::info($output);
    }
}
