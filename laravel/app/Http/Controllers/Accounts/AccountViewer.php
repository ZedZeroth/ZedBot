<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\View\View;
use App\Http\Controllers\CrossDomainInterfaces\ViewerInterface;
use App\Models\Account;

class AccountViewer implements ViewerInterface
{
    /**
     * Show all accounts (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        return view('accounts', [
            'accounts' => Account::all()
                ->sortBy('identifier')
        ]);
    }

    /**
     * Show the profile for a specific account.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        return view('account', [
            'account' =>
                Account::where('identifier', $identifier)
                    ->first()
        ]);
    }

    /**
     * Show all account networks.
     *
     * @return View
     */
    public function showAccountNetworks(): View
    {
        return view(
            'account-networks',
            [
                'accounts' => Account::all()
                    ->unique('network')
            ]
        );
    }

    /**
     * Show all accounts on one account network.
     *
     * @param string $network
     * @return View
     */
    public function showAccountsOnNetwork(
        string $network
    ): View {
        return view(
            'network-accounts',
            [
                'network' => $network,
                'accountsOnNetwork'
                    => Account::where('network', $network)
                        ->get()
            ]
        );
    }
}
