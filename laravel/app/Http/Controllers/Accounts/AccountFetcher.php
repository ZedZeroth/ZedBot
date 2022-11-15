<?php

namespace App\Http\Controllers\Accounts;

class AccountFetcher
{
    /**
     * Fetches accounts via an external API
     * and returns them as an array of DTOs.
     *
     * @param AccountRequestAdapterInterface $requestAdapter
     * @param int $numberOfAccounts
     * @return array
     */
    public function fetch(
        AccountRequestAdapterInterface $requestAdapter,
        int $numberOfAccounts
    ): array {
        return $requestAdapter->request(
            numberOfAccounts: $numberOfAccounts
        );
    }
}
