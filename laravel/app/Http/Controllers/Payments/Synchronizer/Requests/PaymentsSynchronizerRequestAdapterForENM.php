<?php

namespace App\Http\Controllers\Payments\Synchronizer\Requests;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\MultiDomain\Interfaces\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Interfaces\GeneralAdapterInterface;
use App\Http\Controllers\MultiDomain\Adapters\PostAdapterENM;

class PaymentsSynchronizerRequestAdapterForENM implements
    RequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $postParameters
     */
    private array $postParameters;

    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return RequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): RequestAdapterInterface {
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
