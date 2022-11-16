<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentRequestAdapterENM implements PaymentRequestAdapterInterface
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
     * Requests transacations (payments) from ENM
     * and return the response.
     *
     * @param int $numberOfPaymentsToFetch
     * @return array
     */
    public function request(
        int $numberOfPaymentsToFetch
    ): array {
        $this->numberOfPaymentsToFetch = $numberOfPaymentsToFetch;

        return $this
            ->buildPostParameters()
            ->fetchResponse()
            ->parseResponse()
            ->returnResponseBodyArray();
    }

    /**
     * Build the post parameters.
     *
     * @return PaymentRequestAdapterInterface
     */
    private function buildPostParameters(): PaymentRequestAdapterInterface
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
     * @return PaymentRequestAdapterInterface
     */
    private function fetchResponse(): PaymentRequestAdapterInterface
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
     * @return PaymentRequestAdapterInterface
     */
    private function parseResponse(): PaymentRequestAdapterInterface
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
    private function returnResponseBodyArray(): array
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
