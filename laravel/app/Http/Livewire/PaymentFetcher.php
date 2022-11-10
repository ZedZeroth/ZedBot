<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use App\Models\Payment;

class PaymentFetcher extends Component
{
    public $payments;

    public function fetch()
    {
        Artisan::call('payments:fetch');
    }

    public function render()
    {
        $this->payments = Payment::all();
        return view('livewire.payment-fetcher');
    }
}
