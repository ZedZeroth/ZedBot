<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExceptionCatcher
{
    /**
     * Catches exeptions that have been passed
     * up to the initial command.
     *
     * @param Command $command
     * @param string $class
     * @param string $function
     * @param int $line
     * @return void
     */
    public function catch(
        Command $command,
        string $class,
        string $function,
        int $line
    ): void {
        $exceptionCaught = null;
        try {
            try {
                try {
                    try {
                        (new CommandInformer())
                            ->run(command: $command);
                    } catch (\Illuminate\Http\Client\ConnectionException $e) {
                        $exceptionCaught = $e;
                    }
                } catch (\Illuminate\Http\Client\RequestException $e) {
                    $exceptionCaught = $e;
                }
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $exceptionCaught = $e;
            }
        } catch (\TypeError $e) {
            $exceptionCaught = $e;
        }

        if ($exceptionCaught) {
            (new ExceptionInformer())->warn(
                command: $command,
                e: $exceptionCaught,
                class: $class,
                function: $function,
                line: $line
            );
        }
    }
}
