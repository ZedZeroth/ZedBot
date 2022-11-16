<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RequestAdapters\GetAdapterLCS;

class AccountRequestAdapterLCS implements AccountRequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var int $numberOfAccountsToFetch
     * @var Http $response
     * @var int $statusCode
     * @var array $responseBody
     */

     /**
     * Requests blockchain addresses from LCS.
     *
     * @param int $numberOfAccountsToFetch
     * @return array
     */
    public function request(
        int $numberOfAccountsToFetch
    ): array {
        $this->numberOfAccountsToFetch = $numberOfAccountsToFetch;

        return $this
            ->fetchResponse()
            ->parseResponse()
            ->returnResponseBodyArray();
    }

    /**
     * Fetch the response.
     *
     * @return AccountRequestAdapterInterface
     */
    public function fetchResponse(): AccountRequestAdapterInterface
    {
        /**
         * Adapter instantiation is required as
         * some providers use POST and others
         * use GET.
         *
         */
        $this->response = (new GetAdapterLCS())
            ->get(
                endpoint: env('ZED_LCS_WALLETS_ENDPOINT')
            );
        return $this;
    }

    /**
     * Parse the response.
     *
     * @return AccountRequestAdapterInterface
     */
    public function parseResponse(): AccountRequestAdapterInterface
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
