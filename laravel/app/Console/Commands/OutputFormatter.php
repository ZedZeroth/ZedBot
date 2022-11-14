<?php

namespace App\Console\Commands;

class OutputFormatter
{
    /**
     * Format an array of sentences for
     * terminal/log output.
     *
     * @param string $commandName The name of the command
     * calling this method.
     *
     * @param string $startOrEnd Whether the text is being
     * output before or after the command is run.
     *
     * @param array $textArray The lines of text to be formatted.
     *
     * @return array The formatted lines of text.
     */
    public function format(
        string $commandName,
        string $startOrEnd,
        array $textArray
    ): array {
        $outputs = [];

        if ($startOrEnd == 'start') {
            $outputs = array_merge($outputs, [
                '',
                '[+] Calling command: ' . $commandName,
                '----------------------------------------',
            ]);
        }

        foreach ($textArray as $line) {
            array_push(
                $outputs,
                ' - ' . $line
            );
        }

        if ($startOrEnd == 'end') {
            $outputs = array_merge($outputs, [
                '----------------------------------------',
            ]);
        }

        return $outputs;
    }
}
