<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\View\View;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Currencies\CurrencyPopulator;

class CurrencyControllerTest extends TestCase
{
    /**
     * TEST: Show all currencies.
     *
     * @return void
     */
    public function testShowAll(): void
    {
        $this->assertInstanceOf(
            View::class,
            (new CurrencyController())->showAll()
        );
    }

    /**
     * TEST: Show the profile for a given currency.
     *
     * @return void
     */
    public function testShowByValidIdentifier(): void
    {
        $this->assertInstanceOf(
            View::class,
            (new CurrencyController())->showByIdentifier('GBP')
        );
    }

    /**
     * TEST: Show the profile for a given currency.
     *
     * @return void
     */
    public function testShowByInvalidIdentifier(): void
    {
        $this->expectException(\TypeError::class);

        $this->assertInstanceOf(
            View::class,
            (new CurrencyController())->showByIdentifier(0)
        );
    }

    /**
     * TEST: Creates all required currencies.
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $this->assertInstanceOf(
            CurrencyPopulator::class,
            (new CurrencyController())->populate()
        );
    }
}
