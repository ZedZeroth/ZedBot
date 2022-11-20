<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class ExceptionInformer
{
    /**
     * Outputs relevent exception information
     * to the log and the CLI.
     *
     * @param Command $command
     * @param object $e
     * @param string $class
     * @param string $function
     * @param string $line
     * @return void
     */
    public function warn(
        Command $command,
        object $e,
        string $class,
        string $function,
        string $line
    ): void {

        // Explode file paths
        $exceptionPath = explode('\\', $e::class);
        $classPath = explode('\\', $class);

        // Store the details in an array
        $errorDetails = [
            '',
            '[💀] '
            . $exceptionPath[
                array_key_last($exceptionPath)
            ],
            '---------------------------------',
            '',
            'Message:',
            '',
            '"' . $e->getMessage() . '"',
            ''
        ];

        // Add the exception file path
        foreach ($exceptionPath as $directory) {
            array_push(
                $errorDetails,
                'Exception: ' . $directory
            );
        }
        array_push($errorDetails, '');

        // File path of where the exception was caught
        foreach ($classPath as $directory) {
            array_push(
                $errorDetails,
                'Caught by: ' . $directory
            );
        }

        // Add final details
        $errorDetails = array_merge(
            $errorDetails,
            [
                '',
                'Method:    ' . $function,
                'At line:   ' . $line,
                '---------------------------------',
                ''
            ]
        );

        // Push each detail to CLI/log
        foreach ($errorDetails as $detail) {
            $command->warn($detail);
            Log::error($detail);
        }
    }
}
