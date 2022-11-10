<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PaymentController;

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
            $paymentsFetched = (new PaymentController())->sync('Enumis');

            // Output success messages
            $numberOfPaymentsFetched = count($paymentsFetched);
            $output = $numberOfPaymentsFetched . ' payments fetched.';

            if ($numberOfPaymentsFetched) {
                $this->info($output);
                Log::info($output);
            } else {
                $this->warn($output);
                Log::warning($output);
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
