<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Account;

class AccountSynchronizerComponent extends Component
{
    public Collection $accounts;
    public string $numberToFetch = '10';

    /**
     * Calls the 'accounts:sync' command.
     *
     */
    public function sync()
    {
        Artisan::call('accounts:sync ' . $this->numberToFetch);
    }

    /**
     * Renders the view component.
     */
    public function render()
    {
        $this->accounts = Account::all();
        return view('livewire.account-synchronizer-component');
    }
}
