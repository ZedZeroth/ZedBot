<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentSyncRequestAdapterForENM implements PaymentSyncRequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var array $postParameters
     * @var int $statusCode
     * @var array $responseBody
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
            'accountCodes' => env('ZED_ENM_ACCOUNT_CODE'),
            'take' => $numberOfPaymentsToFetch,
            'goFast' => true
        ];
        return $this;
    }

    /**
     * Fetch the response.
     *
     * @return Response
     */
    public function fetchResponse(): Response
    {
        /**
         * Adapter instantiation is required as
         * some providers use POST and others
         * use GET.
         *
         */
        return (new PostAdapterENM())
            ->post(
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $this->postParameters
            );
    }
}
