<?php

namespace App\Http\Controllers\Currencies;

use App\Models\Currency;
use App\Http\Livewire\CurrencyPopulatorComponent;

class CurrencyPopulator
{
    /**
     * Creates all required currencies.
     *
     * @return array
     */
    public function populate(): array
    {
        $currenciesPopulated = [];

        /* USD */
        $currency = Currency::firstOrCreate(
            ['code' => 'USD'],
            [
                'symbol' => 'US$',
                'nameSingular' => 'US dollar',
                'namePlural' => 'US dollars',
                'baseUnitNameSingular' => 'US cent',
                'baseUnitNamePlural' => 'US cents',
                'decimalPlaces' => 2,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
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
            array_push($currenciesPopulated, $currency);
        }

        /* EUR */
        $currency = Currency::firstOrCreate(
            ['code' => 'EUR'],
            [
                'symbol' => '€',
                'nameSingular' => 'euro',
                'namePlural' => 'euros',
                'baseUnitNameSingular' => 'cent',
                'baseUnitNamePlural' => 'cents',
                'decimalPlaces' => 2,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* CAD */
        $currency = Currency::firstOrCreate(
            ['code' => 'CAD'],
            [
                'symbol' => 'C$',
                'nameSingular' => 'Canadian dollar',
                'namePlural' => 'Canadian dollars',
                'baseUnitNameSingular' => 'Canadian cent',
                'baseUnitNamePlural' => 'Canadian cents',
                'decimalPlaces' => 2,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* AUD */
        $currency = Currency::firstOrCreate(
            ['code' => 'AUD'],
            [
                'symbol' => 'A$',
                'nameSingular' => 'Australian dollar',
                'namePlural' => 'Australian dollars',
                'baseUnitNameSingular' => 'Australian cent',
                'baseUnitNamePlural' => 'Australian cents',
                'decimalPlaces' => 2,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* JPY */
        $currency = Currency::firstOrCreate(
            ['code' => 'JPY'],
            [
                'symbol' => '¥',
                'nameSingular' => 'yen',
                'namePlural' => 'yen',
                'baseUnitNameSingular' => 'yen',
                'baseUnitNamePlural' => 'yen',
                'decimalPlaces' => 0,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* CNY */
        $currency = Currency::firstOrCreate(
            ['code' => 'CNY'],
            [
                'symbol' => '元',
                'nameSingular' => 'yuan',
                'namePlural' => 'yuan',
                'baseUnitNameSingular' => 'fen',
                'baseUnitNamePlural' => 'fen',
                'decimalPlaces' => 2,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

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
            array_push($currenciesPopulated, $currency);
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
            array_push($currenciesPopulated, $currency);
        }

        /* USDT-ERC20 */
        $currency = Currency::firstOrCreate(
            ['code' => 'USDT-ERC20'],
            [
                'symbol' => '₮(ERC)',
                'nameSingular' => 'US-Tether (ERC20)',
                'namePlural' => 'US-Tethers (ERC20)',
                'baseUnitNameSingular' => 'micro-US-Tether (ERC20)',
                'baseUnitNamePlural' => 'micro-US-Tethers (ERC20)',
                'decimalPlaces' => 6,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* USDT-TRC20 */
        $currency = Currency::firstOrCreate(
            ['code' => 'USDT-TRC20'],
            [
                'symbol' => '₮(TRC)',
                'nameSingular' => 'US-Tether (TRC20)',
                'namePlural' => 'US-Tethers (TRC20)',
                'baseUnitNameSingular' => 'micro-US-Tether (TRC20)',
                'baseUnitNamePlural' => 'micro-US-Tethers (TRC20)',
                'decimalPlaces' => 6,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* TRX */
        $currency = Currency::firstOrCreate(
            ['code' => 'TRX'],
            [
                'symbol' => 'TRX',
                'nameSingular' => 'TRONIX',
                'namePlural' => 'TRONIX',
                'baseUnitNameSingular' => 'micro-TRONIX',
                'baseUnitNamePlural' => 'micro-TRONIX',
                'decimalPlaces' => 6,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        /* BNB-BSC */
        $currency = Currency::firstOrCreate(
            ['code' => 'BNB-BSC'],
            [
                'symbol' => 'BNB',
                'nameSingular' => 'Binance Coin',
                'namePlural' => 'Binance Coin',
                'baseUnitNameSingular' => 'wei',
                'baseUnitNamePlural' => 'wei',
                'decimalPlaces' => 16,
            ]
        );
        if ($currency->wasRecentlyCreated) {
            array_push($currenciesPopulated, $currency);
        }

        // Refresh the web component
        (new CurrencyPopulatorComponent())->render();

        return $currenciesPopulated;
    }
}
