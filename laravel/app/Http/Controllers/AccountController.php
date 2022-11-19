<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Accounts\AccountViewer;
use App\Console\Commands\CommandDTO;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Http\Controllers\MultiDomain\Adapters\AdapterBuilder;
use App\Http\Controllers\MultiDomain\Adapters\Requester;
use App\Http\Controllers\MultiDomain\Interfaces\ControllerInterface;

class AccountController
    extends Controller
        implements ControllerInterface
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
    public function showNetworks(): View
    {
        return (new AccountViewer())->showNetworks();
    }

    /**
     * Show all accounts on one account network.
     *
     * @param string $network
     * @return View
     */
    public function showOnNetwork(
        string $network
    ): View {
        return (new AccountViewer())
            ->showOnNetwork(
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
        CommandDTO $commandDTO
    ): void {
        (new AccountSynchronizer())
            ->sync(
                (new Requester())->request(
                    adapterDTO:
                        (new AdapterBuilder())->build(
                            models: 'Accounts',
                            action: 'Synchronizer',
                            provider: $commandDTO->data['provider']
                        ),
                    numberToFetch:
                        $commandDTO->data[
                            'numberOfAccountsToFetch'
                        ],
                )
            ); 
        return;
    }
}
