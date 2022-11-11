<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use App\Http\Livewire\CurrencyPopulatorComponent;

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
     */
    public function populate()
    {
        $currenciesCreatedCounter = 0;

        /* BTC */
        $currency = Currency::firstOrCreate(
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
        if ($currency->wasRecentlyCreated) {
            $currenciesCreatedCounter++;
        }

        /* GBP */
        $currency = Currency::firstOrCreate(
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
        if ($currency->wasRecentlyCreated) {
            $currenciesCreatedCounter++;
        }

        /* ETH */
        $currency = Currency::firstOrCreate(
            ['code' => 'ETH'],
            [
                'symbol' => 'Ξ',
                'nameSingular' => 'ether',
                'namePlural' => 'ether',
                'baseUnitNameSingular' => 'wei',
                'baseUnitNamePlural' => 'wei',
                'decimalPlaces' => 18,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            $currenciesCreatedCounter++;
        }

        // Refresh the web component
        (new CurrencyPopulatorComponent())->render();

        return $currenciesCreatedCounter;
    }
}
