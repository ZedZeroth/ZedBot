<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Payment;

class CommandInformer
{
    /**
     * The injected command.
     *
     * @var Command $command
     */
    private Command $command;

    /**
     * Outputs relevent command information
     * before and after running a command.
     *
     * @param Command $command
     * @return Command
     */
    public function run(
        Command $command
    ): Command {
        // Assign command property
        $this->command = $command;

        /**
         * Build and output title.
         *
         */
        if ($this->command->argument('source') == 'cli') {
            $sourceEmoji = '📟';
        } elseif ($this->command->argument('source') == 'browser') {
            $sourceEmoji = '🖱️ ';
        } elseif ($this->command->argument('source') == 'scheduler') {
            $sourceEmoji = '🕑';
        } elseif ($this->command->argument('source') == 'auto') {
            $sourceEmoji = '🤖';
        } else {
            $sourceEmoji = '❓';
        }

        $this->output(
            '[' . $sourceEmoji . '] '
            . $this->command->argument('command')
        );
        $this->output('----------------------------------------');

        /**
         * Output other arguments.
         *
         */
        foreach ($this->command->argument() as $argumentKey => $argument) {
            if ($argumentKey != 'command' and $argumentKey != 'source') {
                $this->output($argumentKey . ': ' . $argument);
            }
        }

        /**
         * Count models and record time.
         *
         */
        $models = [
            'Account' => Account::all()->count(),
            'Currency' => Currency::all()->count(),
            'Payment' => Payment::all()->count(),
        ];
        $startTime = now();

        /**
         * Output 'Running...' and run the command.
         *
         */
        $this->output(
            '... Running "'
            . $this->command->argument('command')
            . '"'
        );
        $this->command->runThisCommand();

        /**
         * Latency
         *
         */
        $latency = now()->diffInMilliseconds($startTime);
        $this->output(
            '... DONE in '
            . number_format($latency, 0, '.', ',')
            . 'ms'
        );

        /**
         * Calculate new models.
         *
         */
        $nothingToUpdate = true;
        foreach ($models as $name => $number) {
            $modelWithPath = '\App\Models\\' . $name;
            $new = $modelWithPath::all()->count() - $number;
            if ($new) {
                $nothingToUpdate = false;
                $this->output($name . ' created: ' . $new);
            }
        }
        if ($nothingToUpdate) {
            $this->output('No new models created.');
        }

        /**
         * Final output
         *
         */
        $this->output('----------------------------------------');
        $this->output('');

        /**
         * Return the command.
         *
         */
        return $this->command;
    }

    /**
     * Outputs a string to the command line
     * and the log.
     *
     * @param string $string
     */
    public function output(
        string $string
    ): void {
        $this->command->info($string);
        Log::info($string);
    }
}
