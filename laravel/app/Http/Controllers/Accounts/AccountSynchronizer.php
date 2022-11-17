<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Account;
use App\Http\Controllers\Accounts\Synchronizer\accountSyncRequestAdapterInterface;
use App\Http\Controllers\Accounts\Synchronizer\accountSyncResponseAdapterInterface;

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
     * @var accountSyncRequestAdapterInterface $accountSyncRequestAdapter
     */
    private accountSyncRequestAdapterInterface $accountSyncRequestAdapter;

    /**
     * The account response adapter.
     *
     * @var accountSyncResponseAdapterInterface $accountSyncResponseAdapter
     */
    private accountSyncResponseAdapterInterface $accountSyncResponseAdapter;

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
        $accountSyncRequestAdapterClass =
            'App\Http\Controllers\Accounts\Synchronizer'
            . '\AccountSyncRequestAdapterFor'
            . strtoupper($accountProvider);
        $this->accountSyncRequestAdapter = new $accountSyncRequestAdapterClass();

        // Build the response adaper
        $accountSyncResponseAdapterClass =
            'App\Http\Controllers\Accounts\Synchronizer'
            . '\AccountSyncResponseAdapterFor'
            . strtoupper($accountProvider);
        $this->accountSyncResponseAdapter = new $accountSyncResponseAdapterClass();

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
            $this->accountSyncRequestAdapter
                ->setNumberOfAccountsToFetch(numberOfAccountsToFetch: $numberOfAccountsToFetch)
                ->buildPostParameters()
                ->fetchResponse()
                ->parseResponse()
                ->returnResponseBodyArray();
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
        $this->DTOs = $this->accountSyncResponseAdapter
            ->setResponseBody(responseBody: $this->responseBody)
            ->buildAccountDTOs()
            ->returnAccountDTOs();
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
