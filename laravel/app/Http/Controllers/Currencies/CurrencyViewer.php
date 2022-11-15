<?php

namespace App\Http\Controllers\Currencies;

use Illuminate\View\View;
use App\Http\Controllers\CrossDomainInterfaces\ViewerInterface;
use App\Models\Currency;

class CurrencyViewer implements ViewerInterface
{
    /**
     * Show all currencies.
     *
     * @return View
     */
    public function showAll(): View
    {
        return view('currencies', [
            'currencies' => Currency::all()
        ]);
    }

    /**
     * Show the profile for a specific currency.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        return view('currency', [
            'currency' =>
                Currency::where('code', $identifier)
                    ->first()
        ]);
    }
}
