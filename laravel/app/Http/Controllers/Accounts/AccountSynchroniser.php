<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Account;
use App\Http\Livewire\AccountSynchronizerComponent;

class AccountSynchroniser
{
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
        // Fetch the payment data
        $requestAdapterClass =
            'App\Http\Controllers\Accounts\AccountRequestAdapter'
            . $provider;

        $accountDTOs = (new AccountFetcher())
            ->fetch(
                requestAdapter: new $requestAdapterClass(),
                numberOfAccounts: $numberOfAccounts,
            );

        foreach ($accountDTOs as $dto) {
            // This will not overrride any pre-existing networkAccountName
            Account::firstOrCreate(
                ['identifier' => $dto->identifier],
                [
                    'network' => $dto->network,
                    'customer_id' => $dto->customer_id,
                    'assumedAccountName' => $dto->assumedAccountName
                ]
            );
        }

        // Refresh view component account count.
        (new AccountSynchronizerComponent())->render();

        return $accountDTOs;
    }
}
