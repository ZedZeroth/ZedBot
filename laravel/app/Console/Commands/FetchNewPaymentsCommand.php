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
    protected $signature = 'payments:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches new payments from payment platforms.';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        // Try the PaymentController 'sync' method
        try {
            // Number of payments to fetch
            // Add this as command argument?
            $numberOfPayments = 100;

            // Initial output messages
            $initialPayments = Payment::all()->count();
            $outputs = [
                '...',
                '#####################################',
                '### payments:fetch command called ###',
                'There are ' . $initialPayments . ' payments.',
                'Fetching ' . $numberOfPayments . ' most recent payments ...',
            ];
            foreach ($outputs as $output) {
                $this->info($output);
                Log::info($output);
            }

            // Run the commanded action
            $paymentsFetched = (new PaymentController())
                ->sync('Enumis', $numberOfPayments);

            // Final output success messages
            $numberOfPaymentsFetched = count($paymentsFetched);
            $finalPayments = Payment::all()->count();

            $outputs = [
                '... ' . $numberOfPaymentsFetched . ' payments fetched.',
                'There are now ' . $finalPayments . ' payments.',
                ($finalPayments - $initialPayments) . ' new payments were created.',
                '### payments:fetch command complete ###',
                '#######################################',
            ];
            foreach ($outputs as $output) {
                if ($numberOfPaymentsFetched) {
                    $this->info($output);
                    Log::info($output);
                } else {
                    $this->warn($output);
                    Log::warning($output);
                }
            }
        } catch (Throwable $e) {
            report($e);
            // Output error messages
            $output = '!ERROR! ' . $e->getMessage();
            $this->error($output);
            Log::error($output);
        }
    }
}
