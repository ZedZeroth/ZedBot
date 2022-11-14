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
     */
    public function sync()
    {
        Artisan::call('payments:sync ' . $this->numberToFetch);
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
