<?php

namespace App\Http\Controllers\Accounts;

interface AccountRequestAdapterInterface
{
    /**
     * Requests accounts from a provider
     * and return the response.
     *
     * @param int $numberOfAccountsToFetch
     * @return array
     */
    public function request(
        int $numberOfAccountsToFetch
    ): array;

    /**
     * Fetch the response.
     *
     * @return AccountRequestAdapterInterface
     */
    public function fetchResponse(): AccountRequestAdapterInterface;

    /**
     * Parse the response.
     *
     * @return AccountRequestAdapterInterface
     */
    public function parseResponse(): AccountRequestAdapterInterface;

    /**
     * Return the responseBody array.
     *
     * @return array
     */
    public function returnResponseBodyArray(): array;
}
