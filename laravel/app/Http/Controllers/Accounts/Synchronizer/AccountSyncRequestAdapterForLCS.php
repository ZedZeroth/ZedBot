<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;
use App\Http\Controllers\RequestAdapters\GetAdapterLCS;

class AccountSyncRequestAdapterForLCS implements AccountSyncRequestAdapterInterface
{
     /**
     * Build the post parameters.
     *
     * @param int $numberOfAccountsToFetch
     * @return AccountSyncRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberOfAccountsToFetch
        ): AccountSyncRequestAdapterInterface
    {
        // No post parameters for LCS
        return $this;
    }

    /**
     * Fetch the response.
     * 
     * @param GeneralRequestAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralRequestAdapterInterface $getOrPostAdapter
    ): Response {
        return (new $getOrPostAdapter)
            ->get(
                endpoint: env('ZED_LCS_WALLETS_ENDPOINT')
            );
    }
}
