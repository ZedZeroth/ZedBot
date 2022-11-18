<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;
use App\Http\Controllers\RequestAdapters\GetAdapterLCS;

class AccountsSynchronizerRequestAdapterForLCS implements
    AccountsSynchronizerRequestAdapterInterface,
    GeneralAdapterInterface
{
     /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return AccountsSynchronizerRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
        ): AccountsSynchronizerRequestAdapterInterface
    {
        // No post parameters for LCS
        return $this;
    }

    /**
     * Fetch the response.
     * 
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): Response {
        return (new $getOrPostAdapter)
            ->get(
                endpoint: env('ZED_LCS_WALLETS_ENDPOINT')
            );
    }
}
