<?php

namespace App\Http\Controllers\MultiDomain;

use App\Models\Currency;

class MoneyFormatter
{
    /**
     * Formats money from base units into
     * its primary denomination.
     *
     * @param int $amount
     * @param Currency $currency
     * @return string
     */
    public function format(
        int $amount,
        Currency $currency
    ): string {
        return number_format(
            $amount / pow(10, $currency->decimalPlaces),
            $currency->decimalPlaces,
            '.',
            ',',
        );
    }
}
