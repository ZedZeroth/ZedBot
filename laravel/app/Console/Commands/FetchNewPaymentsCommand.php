<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PaymentController;
use App\Models\Payment;

class FetchNewPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:fetch {number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches new payments from payment platforms.';

    /**
     * Initial output message
     *
     * @param int $numberOfPaymentsToFetch
     * @param int $initialPayments
     */
    public function start(
        int $numberOfPaymentsToFetch,
        int $initialPayments
    ) {
        $outputs = [
            'Current number of payments:         ' . $initialPayments,
            'Number of recent payments to fetch: ' . $numberOfPaymentsToFetch,
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
    public function handle()
    {
        $numberOfPaymentsToFetch = $this->argument('number');
        $initialPayments = Payment::all()->count();

        // Initialize
        /* Add payment number as command argument */
        $this->start(
            numberOfPaymentsToFetch: $numberOfPaymentsToFetch,
            initialPayments: $initialPayments,
        );

        // Run the commanded action
        $paymentsFetched = (new PaymentController())
            ->sync('Enumis', $numberOfPaymentsToFetch);

        // Finalize
        $this->finish(
            numberOfPaymentsToFetch: $numberOfPaymentsToFetch,
            initialPayments: $initialPayments,
            paymentsFetched: $paymentsFetched,
        );
    }

    /**
     * Final output message
     *
     * @param int $numberOfPaymentsToFetch
     * @param int $initialPayments
     * @param array $paymentsFetched
     */
    public function finish(
        int $numberOfPaymentsToFetch,
        int $initialPayments,
        array $paymentsFetched
    ) {
        $numberOfPaymentsFetched = count($paymentsFetched);
        $finalPayments = Payment::all()->count();

        $outputs = [
            '... [ DONE ] ...',
            'Payments successfully fetched: ' . $numberOfPaymentsFetched,
            'New total number of payments:  ' . $finalPayments,
            'New payments created:          ' . ($finalPayments - $initialPayments),
        ];

        $formattedOutputs = (new OutputFormatter())->format(
            commandName: $this->signature,
            startOrEnd: 'end',
            textArray: $outputs
        );

        foreach ($formattedOutputs as $output) {
            if ($numberOfPaymentsFetched) {
                $this->info($output);
                Log::info($output);
            } else {
                $this->warn($output);
                Log::warning($output);
            }
        }
    }
}
