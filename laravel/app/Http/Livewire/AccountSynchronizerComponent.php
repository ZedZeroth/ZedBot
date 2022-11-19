<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Account;
use Illuminate\View\View;

class AccountSynchronizerComponent extends Component
{
    public Collection $accounts;
    public string $numberToFetch = '10';

    /**
     * Calls the 'accounts:sync' command.
     *
     */
    public function sync(string $provider): void
    {
        Artisan::call(
            'accounts:sync browser '
            . $provider
            . ' '
            . $this->numberToFetch
        );
    }

    /**
     * Renders the view component.
     * 
     * @return View
     */
    public function render(): View
    {
        $this->accounts = Account::all();
        return view('livewire.account-synchronizer-component');
    }
}
