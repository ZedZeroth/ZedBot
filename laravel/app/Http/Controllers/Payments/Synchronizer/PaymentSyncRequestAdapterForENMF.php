<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentSyncRequestAdapterForENMF implements PaymentSyncRequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $postParameters
     */

    /**
     * Build the post parameters.
     *
     * @param int $numberOfPaymentsToFetch
     * @return PaymentSyncRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberOfPaymentsToFetch
    ): PaymentSyncRequestAdapterInterface {
        $this->postParameters = [
            'accountCode' => env('ZED_ENM_ACCOUNT_CODE'),
            'take' => $numberOfPaymentsToFetch,
            'goFast' => true
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
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
