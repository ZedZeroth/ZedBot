<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Account;

class AccountViewer
{
    /**
     * Show all account networks.
     *
     * @return \Illuminate\View\View
     */
    public function viewNetworks()
    {
        return view(
            'account-networks',
            ['accounts' => Account::all()->unique('network')]
        );
    }

    /**
     * Show all accounts on one account network.
     *
     * @param  string  $network
     * @return \Illuminate\View\View
     */
    public function viewAccountsOnNetwork($network)
    {
        return view(
            'network-accounts',
            [
                'network' => $network,
                'accountsOnNetwork' => Account::where('network', $network)->get(),
            ]
        );
    }

    /**
     * Show one account by its identifier.
     *
     * @param  string  $identifier
     * @return \Illuminate\View\View
     */
    public function viewByIdentifier($identifier)
    {
        return view(
            'account',
            ['account' => Account::where('identifier', $identifier)->firstOrFail()]
        );
    }
}
