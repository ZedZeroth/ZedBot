<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentsSynchronizerRequestAdapterForENM implements
    PaymentsSynchronizerRequestAdapterInterface,
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
     * @return PaymentsSynchronizerRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): PaymentsSynchronizerRequestAdapterInterface {
        $this->postParameters = [
            'accountCode' => env('ZED_ENM_ACCOUNT_CODE'),
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
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
