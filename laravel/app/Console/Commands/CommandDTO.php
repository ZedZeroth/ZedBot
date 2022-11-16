<?php

namespace App\Console\Commands;

class CommandDTO
{
    /**
     * The command data transfer object
     * for moving account data between
     * commands and controllers.
     */
    public function __construct(
        public array $data
    ) {
    }
}
