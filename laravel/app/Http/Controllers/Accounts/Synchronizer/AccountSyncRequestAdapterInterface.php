<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;

interface AccountSyncRequestAdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberOfAccountsToFetch
     * @return AccountSyncRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberOfAccountsToFetch
        ): AccountSyncRequestAdapterInterface;

    /**
     * Fetch the response.
     * 
     * @param GeneralRequestAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralRequestAdapterInterface $getOrPostAdapter
    ): Response;
}
