<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CurrencyController;

class CurrencyCreateAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:createAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all currencies in the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new CurrencyController)->createAll();
        $this->info('The command was successful!');
        return Command::SUCCESS;
    }
}
