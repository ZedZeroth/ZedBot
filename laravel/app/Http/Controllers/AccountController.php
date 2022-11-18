<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Console\Commands\CommandDTO;
use App\Http\Controllers\Accounts\AccountViewer;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Http\Controllers\MultiDomain\ResponseDecoder;

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
     * Fetches accounts from external providers
     * and creates any new ones that do not exist.
     *
     * @param CommandDTO $dto
     * @return void
     */
    public function sync(
        CommandDTO $dto
    ): void {
        (new AccountSynchronizer())
            ->selectAdapters($dto->data['provider'])
            ->requestAccountsAndAdaptResponse(
                $dto->data['numberOfAccountsToFetch'],
                responseDecoder: new ResponseDecoder()
            )
            ->createNewAccounts();
        return;
    }
}
