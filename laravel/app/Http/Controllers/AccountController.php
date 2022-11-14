<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Accounts\AccountViewer;

class AccountController extends Controller
{
    /**
     * Show all account networks.
     *
     * @return \Illuminate\View\View
     */
    public function viewNetworks()
    {
        return (new AccountViewer())->viewNetworks();
    }

    /**
     * Show all accounts on one payment network.
     *
     * @param  string  $network
     * @return \Illuminate\View\View
     */
    public function viewAccountsOnNetwork($network)
    {
        return (new AccountViewer())->viewAccountsOnNetwork(network: $network);
    }

    /**
     * Show one account by its identifier.
     *
     * @param  string  $identifier
     * @return \Illuminate\View\View
     */
    public function viewByIdentifier($identifier)
    {
        return (new AccountViewer())->viewByIdentifier(identifier: $identifier);
    }
}
