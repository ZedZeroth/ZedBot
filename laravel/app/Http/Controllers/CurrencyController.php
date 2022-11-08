<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

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
     * Create all currencies in the database.
     *
     */
    public function createAll()
    {
        /* BTC */
        $currency = Currency::updateOrCreate(
            ['code' => 'BTC'],
            [
                'symbol' => '₿',
                'nameSingular' => 'bitcoin',
                'namePlural' => 'bitcoin',
                'baseUnitNameSingular' => 'sat',
                'baseUnitNamePlural' => 'sats',
                'decimalPlaces' => 8,
            ]
        );

        /* GBP */
        $currency = Currency::updateOrCreate(
            ['code' => 'GBP'],
            [
                'symbol' => '£',
                'nameSingular' => 'pound',
                'namePlural' => 'pounds',
                'baseUnitNameSingular' => 'penny',
                'baseUnitNamePlural' => 'pence',
                'decimalPlaces' => 2,
            ]
        );
    }
}
