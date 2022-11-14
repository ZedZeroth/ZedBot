<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use App\Models\Currency;

class CurrencyPopulatorComponent extends Component
{
    public $currencies;

    public function populate()
    {
        Artisan::call('currencies:populate');
        $this->render();
    }

    public function render()
    {
        $this->currencies = Currency::all();
        return view('livewire.currency-populator-component');
    }
}
