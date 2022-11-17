<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

interface AccountSyncResponseAdapterInterface
{
    /**
     * Sets the response body.
     *
     * @param array $responseBody
     * @return accountSyncResponseAdapterInterface
     */
    public function setResponseBody(
        array $responseBody
    ): accountSyncResponseAdapterInterface;

    /**
     * Build the account DTOs.
     *
     * @return accountSyncResponseAdapterInterface
     */
    public function buildAccountDTOs(): accountSyncResponseAdapterInterface;

    /**
     * Return the account DTOs.
     *
     * @return array
     */
    public function returnAccountDTOs(): array;
}
