<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Account;
use App\Http\Controllers\Accounts\Synchronizer\accountSyncRequestAdapterInterface;
use App\Http\Controllers\Accounts\Synchronizer\accountSyncResponseAdapterInterface;
use App\Http\Controllers\MultiDomain\ResponseDecoder;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;

class AccountSynchronizer
{
    /*
     * DTOs are stored as an array property.
     * Each request/response adapter is
     * also stored as a property.
     *
     * @var array $DTOs
     * @var accountSyncRequestAdapterInterface $accountSyncRequestAdapter
     * @var accountSyncResponseAdapterInterface $accountSyncResponseAdapter
     */
    private array $DTOs;
    private accountSyncRequestAdapterInterface $accountSyncRequestAdapter;
    private accountSyncResponseAdapterInterface $accountSyncResponseAdapter;
    private GeneralRequestAdapterInterface $GetOrPostAdapter;

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

        $synchronizerPath = 'App\Http\Controllers\Accounts\Synchronizer';

        // Build the request adaper
        $accountSyncRequestAdapterClass = $synchronizerPath
            . '\AccountSyncRequestAdapterFor'
            . strtoupper($accountProvider);
        $this->accountSyncRequestAdapter = new $accountSyncRequestAdapterClass();

        // Build the response adaper
        $accountSyncResponseAdapterClass = $synchronizerPath
            . '\AccountSyncResponseAdapterFor'
            . strtoupper($accountProvider);
        $this->accountSyncResponseAdapter = new $accountSyncResponseAdapterClass();

        // Build the general get/post adapter
        $requestAdapterPath = 'App\Http\Controllers\RequestAdapters';
        if (in_array(
            $accountProvider,
            explode(',', env('USES_POST_TO_GET'))
        )) {
            $getOrPostAdapterClass = $requestAdapterPath
                . '\PostAdapterFor'
                . strtoupper($accountProvider);
        } else {
            $getOrPostAdapterClass = $requestAdapterPath
                . '\GetAdapterFor'
                . strtoupper($accountProvider);
        }
        $this->GetOrPostAdapter = new $getOrPostAdapterClass();

        return $this;
    }

    /**
     * Requests a response from the provider,
     * decodes it, and adapts it into DTOs.
     *
     * @param int $numberOfAccountsToFetch
     * @param ResponseDecoder $responseDecoder
     * @return AccountSynchronizer
     */
    public function requestAccountsAndAdaptResponse(
        int $numberOfAccountsToFetch,
        ResponseDecoder $responseDecoder
    ): AccountSynchronizer {
        
        //Fetch the response
        $response =
            $this->accountSyncRequestAdapter
                ->buildPostParameters(
                    numberOfAccountsToFetch: $numberOfAccountsToFetch
                )
                ->fetchResponse(
                    getOrPostAdapter: $this->GetOrPostAdapter
                );

        // Decode the response
        $responseBody =
            $responseDecoder->decode($response);

        // Adapt the response
        $this->DTOs = $this->accountSyncResponseAdapter
            ->buildAccountDTOs(
                responseBody: $responseBody
            );
        return $this;
    }

    /**
     * Set the DTOs (when syncing payments)
     *
     * @param array $DTOs
     * @return AccountSynchronizer
     */
    public function setDTOs(
        array $DTOs
    ): AccountSynchronizer {
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
