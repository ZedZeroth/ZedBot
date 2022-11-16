<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Account;
use App\Http\Livewire\AccountSynchronizerComponent;

class AccountSynchronizer
{
    /*
     * DTOs are stored as an array property.
     *
     * @var array $DTOs
     */
    private array $DTOs;

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
                    'label' => $dto->label,
                    'currency_id' => $dto->currency_id,
                    'balance' => $dto->balance,
                ]
            );
        }

        // Refresh view component account count.
        (new AccountSynchronizerComponent())->render();

        return $accountDTOs;
    }

    /////////////////////////////////////////////////////////
    ////////////////////////REFACTORED//////////////////////

    /**
     * Set DTOs.
     *
     * @param array $DTOs
     * @return AccountSynchronizer
     */
    public function setDTOs(array $DTOs): AccountSynchronizer
    {
        $this->DTOs = $DTOs;
        return $this;
    }

    /**
     * Uses the DTOs to create accounts for
     * any that do not already exist.
     *
     * @return AccountSynchronizer
     */
    public function createNewAccounts(): AccountSynchronizer
    {
        foreach ($this->DTOs as $dto) {
            Account::firstOrCreate(
                ['identifier' => $dto->identifier],
                [
                    'network' => $dto->network,
                    'customer_id' => $dto->customer_id,
                    'currency_id' => $dto->currency_id,
                    'balance' => $dto->balance,
                ]
            );

            // If a networkAccountName is passed then update it
            if ($dto->networkAccountName) {
                Account::where('identifier', $dto->identifier)
                ->update(['networkAccountName' => $dto->networkAccountName]);
            }

            // If a label is passed then update it
            if ($dto->label) {
                Account::where('identifier', $dto->identifier)
                ->update(['label' => $dto->label]);
            }
        }

        return $this;
    }
}
