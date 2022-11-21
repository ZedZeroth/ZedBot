<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\View\View;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Accounts\AccountSynchonizer;
use App\Console\Commands\SyncCommandDTO;

class AccountControllerTest extends TestCase
{
    /**
     * TEST: Show all accounts.
     *
     * @return void
     */
    public function testShowAll(): void
    {
        $this->assertInstanceOf(
            View::class,
            (new AccountController())->showAll()
        );
    }

    /**
     * TEST: Show the details of a given account.
     *
     * @return void
     */
    public function testShowByIdentifierWithValidParameters(): void
    {
        $this->assertInstanceOf(
            View::class,
            (new AccountController())->showByIdentifier('fps::gbp::400200::85845378')
        );
    }

    /**
     * TEST: Show the details of a given account.
     *
     * @return void
     */
    public function testShowByIdentifierWithInvalidParameters(): void
    {
        $this->expectException(\TypeError::class);

        $this->assertInstanceOf(
            View::class,
            (new AccountController())->showByIdentifier(0)
        );
    }

    /**
     * TEST: Show all account networks.
     *
     * @return void
     */
    public function testShowNetworks(): void
    {
        $this->assertInstanceOf(
            View::class,
            (new AccountController())->showNetworks()
        );
    }

    /**
     * TEST: Show all accounts on one account network.
     *
     * @return void
     */
    public function testShowOnNetworkWithValidParameters(): void
    {
        $this->assertInstanceOf(
            View::class,
            (new AccountController())->showOnNetwork('GBP')
        );
    }

    /**
     * TEST: Show all accounts on one account network.
     *
     * @return void
     */
    public function testShowOnNetworkWithInvalidParameters(): void
    {
        $this->expectException(\TypeError::class);

        $this->assertInstanceOf(
            View::class,
            (new AccountController())->showOnNetwork(0)
        );
    }

    /**
     * TEST: Fetches accounts from external providers
     * and creates any new ones that do not exist.
     *
     * @return void
     */
    public function testSyncWithValidParameters(): void
    {
        $this->assertNull(
            (new AccountController())->sync(
                new SyncCommandDTO(
                    provider: 'ENM',
                    numberToFetch: 0
                )
            )
        );
    }

    /**
     * TEST: Fetches accounts from external providers
     * and creates any new ones that do not exist.
     *
     * @return void
     */
    public function testSyncWithInvalidParameters(): void
    {
        $this->expectException(\TypeError::class);

        $this->assertNull(
            (new AccountController())->sync()
        );
    }
}
