<?php

declare(strict_types=1);

namespace App\Console\Commands;

/**
 * The command data transfer object
 * for moving command data between
 * commands and controllers.
 */
class CommandDTO
{
    public function __construct(
        public array $data
    ) {
    }
}
