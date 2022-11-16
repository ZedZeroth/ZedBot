<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Currency;

class CurrencyPopulatorComponent extends Component
{
    public $currencies;

    public function populate()
    {
        try {
            Artisan::call('currencies:populate browser');
        } catch (\Symfony\Component\Console\Exception\RuntimeException $e) {
            Log::error(__METHOD__ . ' [' . __LINE__ . '] ' . $e->getMessage());
        }
        $this->render();
    }

    public function render()
    {
        $this->currencies = Currency::all();
        return view('livewire.currency-populator-component');
    }
}
