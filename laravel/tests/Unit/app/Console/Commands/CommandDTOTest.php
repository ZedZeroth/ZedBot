<?php

declare(strict_types=1);

namespace Tests\Unit\App\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Console\Commands\CommandDTO;

class CommandDTOTest extends TestCase
{
    /**
     * TEST: The command data transfer object
     * for moving command data between
     * commands and controllers.
     */
    public function testConstructWithValidParameters(): void
    {
        $this->assertInstanceOf(
            CommandDTO::class,
            new CommandDTO([])
        );
    }

    /**
     * TEST: The command data transfer object
     * for moving command data between
     * commands and controllers.
     */
    public function testConstructWithInvalidParameters(): void
    {
        $this->expectException(\TypeError::class);

        $this->assertInstanceOf(
            CommandDTO::class,
            new CommandDTO()
        );
    }
}
