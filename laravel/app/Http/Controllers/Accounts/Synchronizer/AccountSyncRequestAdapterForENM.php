<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;
use App\Http\Controllers\RequestAdapters\PostAdapterInterface;

class AccountSyncRequestAdapterForENM implements AccountSyncRequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $postParameters
     */

    /**
     * Build the post parameters.
     *
     * @param int $numberOfAccountsToFetch
     * @return AccountSyncRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberOfAccountsToFetch
    ): AccountSyncRequestAdapterInterface {
        $this->postParameters = [
            'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
            'take' => $numberOfAccountsToFetch
        ];
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
        return ($getOrPostAdapter)
            ->post(
                endpoint: env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
