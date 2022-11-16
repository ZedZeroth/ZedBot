<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Payment;

class PaymentSynchronizerComponent extends Component
{
    public Collection $payments;
    public string $numberToFetch = '10';

    /**
     * Calls the 'payments:sync' command.
     *
     * @param string $provider
     */
    public function sync(string $provider): void
    {
        try {
            Artisan::call(
                'payments:sync browser '
                . $provider
                . ' '
                . $this->numberToFetch
            );
        } catch (\Symfony\Component\Console\Exception\RuntimeException $e) {
            Log::error(__METHOD__ . ' [' . __LINE__ . '] ' . $e->getMessage());
        }
    }

    /**
     * Renders the view component.
     */
    public function render()
    {
        $this->payments = Payment::all();
        return view('livewire.payment-synchronizer-component');
    }
}
