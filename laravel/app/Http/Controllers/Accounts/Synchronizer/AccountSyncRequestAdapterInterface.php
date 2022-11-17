<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

interface AccountSyncRequestAdapterInterface
{
    /**
     * Set the number of accounts to fetch.
     *
     * @param int $numberOfAccountsToFetch
     * @return accountSyncRequestAdapterInterface
     */
    public function setNumberOfAccountsToFetch(
        int $numberOfAccountsToFetch
    ): accountSyncRequestAdapterInterface;

    /**
     * Build the post parameters.
     *
     * @return accountSyncRequestAdapterInterface
     */
    public function buildPostParameters(): accountSyncRequestAdapterInterface;

    /**
     * Fetch the response.
     *
     * @return accountSyncRequestAdapterInterface
     */
    public function fetchResponse(): accountSyncRequestAdapterInterface;

    /**
     * Parse the response.
     *
     * @return accountSyncRequestAdapterInterface
     */
    public function parseResponse(): accountSyncRequestAdapterInterface;

    /**
     * Return the responseBody array.
     *
     * @return array
     */
    public function returnResponseBodyArray(): array;
}
