<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Account;

class AccountSynchronizer
{
    /*
     * DTOs are stored as an array property.
     *
     * @var array $DTOs
     */
    private array $DTOs;

    /**
     * The response returned by the request.
     *
     * @var array $responseBody
     */
    private array $responseBody;

    /**
     * The account request adapter.
     *
     * @var AccountRequestAdapterInterface $accountRequestAdapter
     */
    private AccountRequestAdapterInterface $accountRequestAdapter;

    /**
     * The account response adapter.
     *
     * @var AccountResponseAdapterInterface $accountResponseAdapter
     */
    private AccountResponseAdapterInterface $accountResponseAdapter;

    /**
     * Builds the correct adapters for
     * the specified account provider.
     *
     * @param string $accountProvider
     * @return AccountSynchronizer
     */
    public function selectAdapters(
        string $accountProvider
    ): AccountSynchronizer {

        // Build the request adaper
        $accountRequestAdapterClass =
            'App\Http\Controllers\Accounts\AccountRequestAdapter'
            . strtoupper($accountProvider);
        $this->accountRequestAdapter = new $accountRequestAdapterClass();

        // Build the response adaper
        $accountResponseAdapterClass =
            'App\Http\Controllers\Accounts\AccountResponseAdapter'
            . strtoupper($accountProvider);
        $this->accountResponseAdapter = new $accountResponseAdapterClass();

        return $this;
    }

    /**
     * Requests a response from the provider
     * and returns the reponseBody array.
     *
     * @param int $numberOfAccountsToFetch
     * @return AccountSynchronizer
     */
    public function requestAccounts(
        int $numberOfAccountsToFetch
    ): AccountSynchronizer {
        $this->responseBody =
            $this->accountRequestAdapter
                ->request($numberOfAccountsToFetch);
        return $this;
    }

    /**
     * Adapts the responseBody array into
     * an array of DTOs.
     *
     * @return AccountSynchronizer
     */
    public function adaptResponse(): AccountSynchronizer
    {
        $this->DTOs = $this->accountResponseAdapter
            ->adapt(responseBody: $this->responseBody);
        return $this;
    }

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
