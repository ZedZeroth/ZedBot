<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SchedulerIsRunningCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:running';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Messages the console that the scheduler is running.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /* Output messages */
        $output = 'The scheduler is running ...';
        $this->info($output);
        Log::info($output);
    }
}
