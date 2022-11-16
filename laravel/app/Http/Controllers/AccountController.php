<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Accounts\AccountViewer;
use App\Http\Controllers\Accounts\AccountSynchronizer;

class AccountController extends Controller
{
    /**
     * Show all accounts (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new AccountViewer())->showAll();
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
        return (new AccountViewer())
            ->showByIdentifier(
                identifier: $identifier
            );
    }

    /**
     * Show all account networks.
     *
     * @return View
     */
    public function showAccountNetworks(): View
    {
        return (new AccountViewer())->showAccountNetworks();
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
        return (new AccountViewer())
            ->showAccountsOnNetwork(
                network: $network
            );
    }

    /**
     * Fetches recent accounts from external providers
     * and creates any new ones that do not exist.
     *
     * @param string $provider
     * @param int $numberOfAccounts
     * @return array
     */
    public function sync($provider, $numberOfAccounts)
    {
        return (new AccountSynchronizer())
            ->sync(
                provider: $provider,
                numberOfAccounts: $numberOfAccounts,
            );
    }
}
