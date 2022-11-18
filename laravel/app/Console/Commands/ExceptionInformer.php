<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Console\Commands\Exception;

class ExceptionInformer
{
    /**
     * Outputs relevent exception information
     * to the log and CLI.
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
            $exceptionPath = explode('\\', $e::class);
            $classPath = explode('\\', $class);
            
            $errorDetails = [
                '',
                '[💀] ' . $exceptionPath[array_key_last($exceptionPath)],
                '---------------------------------',
                '',
                'Message:',
                '',
                '"' . $e->getMessage() . '"',
                ''
            ];

            foreach ($exceptionPath as $directory) {
                array_push(
                    $errorDetails,
                    'Exception: ' . $directory
                );
            }

            array_push($errorDetails,'');

            foreach ($classPath as $directory) {
                array_push(
                    $errorDetails,
                    'Caught by: ' . $directory
                );
            }

            $errorDetails = array_merge(
                $errorDetails, [
                    '',
                    'Method:    ' . $function,
                    'At line:   ' . $line,
                    '---------------------------------',
                    ''
                ]
            );
            
            foreach ($errorDetails as $detail) {
                $command->warn($detail);
                Log::error($detail);
            }
    }
}
