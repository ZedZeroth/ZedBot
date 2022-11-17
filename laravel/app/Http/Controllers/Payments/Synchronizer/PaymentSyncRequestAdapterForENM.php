<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentSyncRequestAdapterForENM implements PaymentSyncRequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var int $numberOfPaymentsToFetch
     * @var array $postParameters
     * @var Http $response
     * @var int $statusCode
     * @var array $responseBody
     */

     /**
     * Set the number of payments to fetch.
     *
     * @param int $numberOfPaymentsToFetch
     * @return PaymentSyncRequestAdapterInterface
     */
    public function setNumberOfPaymentsToFetch(
        int $numberOfPaymentsToFetch
    ): PaymentSyncRequestAdapterInterface
    {
        $this->numberOfPaymentsToFetch = $numberOfPaymentsToFetch;
        return $this;
    }

    /**
     * Build the post parameters.
     *
     * @return PaymentSyncRequestAdapterInterface
     */
    public function buildPostParameters(): PaymentSyncRequestAdapterInterface
    {
        $this->postParameters = [
            'accountCode' => env('ZED_ENM_ACCOUNT_CODE'),
            'take' => $this->numberOfPaymentsToFetch
        ];
        return $this;
    }

    /**
     * Fetch the response.
     *
     * @return PaymentSyncRequestAdapterInterface
     */
    public function fetchResponse(): PaymentSyncRequestAdapterInterface
    {
        /**
         * Adapter instantiation is required as
         * some providers use POST and others
         * use GET.
         *
         */
        $this->response = (new PostAdapterENM())
            ->post(
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $this->postParameters
            );
        return $this;
    }

     /**
     * Parse the response.
     *
     * @return PaymentSyncRequestAdapterInterface
     */
    public function parseResponse(): PaymentSyncRequestAdapterInterface
    {
        $this->statusCode = $this->response->status();
        $this->responseBody = json_decode(
            $this->response->getBody(),
            true
        );
        return $this;
    }

    /**
     * Return the responseBody array.
     *
     * @return array
     */
    public function returnResponseBodyArray(): array
    {
        if ($this->statusCode == 200) {
            return $this->responseBody;
        } else {
            Log::error('Status code: ' . $this->statusCode);
            if (!empty($this->responseBody['responseStatus']['message'])) {
                Log::error($this->responseBody['responseStatus']['message']);
            }
            return [];
        }
    }
}
