<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;
use App\Http\Controllers\RequestAdapters\PostAdapterInterface;

class AccountsSynchronizerRequestAdapterForENM implements
    AccountsSynchronizerRequestAdapterInterface,
    GeneralAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $postParameters
     */

    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return AccountsSynchronizerRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): AccountsSynchronizerRequestAdapterInterface {
        $this->postParameters = [
            'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
            'take' => $numberToFetch
        ];
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
        return ($getOrPostAdapter)
            ->post(
                endpoint: env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
