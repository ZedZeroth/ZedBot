<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RequestAdapters\GetAdapterLCS;

class AccountSyncRequestAdapterForLCS implements AccountSyncRequestAdapterInterface
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
     * Set the number of accounts to fetch.
     *
     * @param int $numberOfAccountsToFetch
     * @return accountSyncRequestAdapterInterface
     */
    public function setNumberOfAccountsToFetch(
        int $numberOfAccountsToFetch
    ): accountSyncRequestAdapterInterface {
        $this->numberOfAccountsToFetch = $numberOfAccountsToFetch;
        return $this;
    }

    /**
     * Build the post parameters.
     *
     * @return accountSyncRequestAdapterInterface
     */
    public function buildPostParameters(): accountSyncRequestAdapterInterface
    {
        // No post parameters for LCS
        return $this;
    }

    /**
     * Fetch the response.
     *
     * @return accountSyncRequestAdapterInterface
     */
    public function fetchResponse(): accountSyncRequestAdapterInterface
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
     * @return accountSyncRequestAdapterInterface
     */
    public function parseResponse(): accountSyncRequestAdapterInterface
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
