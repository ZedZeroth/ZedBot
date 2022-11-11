<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Http\Controllers\Currencies\CurrencyPopulator;

class CurrencyController extends Controller
{
    /**
     * Show all currencies.
     *
     * @return \Illuminate\View\View
     */
    public function showAll()
    {
        return view('currencies', [
            'currencies' => Currency::all()
        ]);
    }

    /**
     * Show the profile for a given currency.
     *
     * @param  string  $code
     * @return \Illuminate\View\View
     */
    public function show($code)
    {
        return view('currency', [
            'currency' => Currency::findOrFail($code)
        ]);
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
