<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class AccountRequestAdapterENM implements AccountRequestAdapterInterface
{
    /**
     * Properties required to perform the request.
     *
     * @var int $numberOfAccountsToFetch
     * @var array $postParameters
     * @var Http $response
     * @var int $statusCode
     * @var array $responseBody
     */

     /**
     * Requests accounts from ENM
     * and return the response.
     *
     * @param int $numberOfAccountsToFetch
     * @return array
     */
    public function request(
        int $numberOfAccountsToFetch
    ): array {
        $this->numberOfAccountsToFetch = $numberOfAccountsToFetch;

        return $this
            ->buildPostParameters()
            ->fetchResponse()
            ->parseResponse()
            ->returnResponseBodyArray();
    }

    /**
     * Build the post parameters.
     *
     * @return AccountRequestAdapterInterface
     */
    private function buildPostParameters(): AccountRequestAdapterInterface
    {
        $this->postParameters = [
            'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
            'take' => $this->numberOfAccountsToFetch
        ];
        return $this;
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
        $this->response = (new PostAdapterENM())
            ->post(
                endpoint: env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
                postParameters: $this->postParameters
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
