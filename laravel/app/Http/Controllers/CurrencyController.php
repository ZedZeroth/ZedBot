<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Currency;
use App\Http\Controllers\Currencies\CurrencyPopulator;
use App\Http\Controllers\Currencies\CurrencyViewer;

class CurrencyController extends Controller
{
    /**
     * Show all currencies.
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new CurrencyViewer())->showAll();
    }

    /**
     * Show the profile for a given currency.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        return (new CurrencyViewer())->showByIdentifier(
            identifier: $identifier
        );
    }

    /**
     * Creates all required currencies.
     *
     * @return array
     */
    public function populate(): array
    {
        return (new CurrencyPopulator())->populate();
    }
}
