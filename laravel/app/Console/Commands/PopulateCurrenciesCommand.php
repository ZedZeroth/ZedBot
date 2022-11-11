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
     * Initial output message
     *
     * @param int $initialCurrencies
     */
    public function start($initialCurrencies)
    {
        $outputs = [
            'Current number of currencies:      ' . $initialCurrencies,
            '... Populating ...',
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
    public function handle()
    {
        $initialCurrencies = Currency::all()->count();

        // Initialize
        $this->start(
            initialCurrencies: $initialCurrencies,
        );

        // Run the commanded action
        $currenciesPopulated = (new CurrencyController())->populate();

        // Finalize
        $this->finish(
            initialCurrencies: $initialCurrencies,
            currenciesPopulated: $currenciesPopulated,
        );
    }

    /**
     * Final output message
     *
     * @param int $initialCurrencies
     * @param int $paymentsFetched
     */
    public function finish($initialCurrencies, $currenciesPopulated)
    {
        $numberOfCurrenciesPopulated = $currenciesPopulated;
        $finalCurrencies = Currency::all()->count();

        $outputs = [
            '... [ DONE ] ...',
            'Currencies successfully populated: ' . $numberOfCurrenciesPopulated,
            'New total number of currencies:    ' . $finalCurrencies,
            'New currencies created:            ' . ($finalCurrencies - $initialCurrencies),
        ];

        $formattedOutputs = (new OutputFormatter())->format(
            commandName: $this->signature,
            startOrEnd: 'end',
            textArray: $outputs
        );

        foreach ($formattedOutputs as $output) {
            $this->info($output);
            Log::info($output);
        }
    }
}
