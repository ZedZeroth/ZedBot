<?php

namespace App\Http\Controllers\Accounts;

interface AccountResponseAdapterInterface
{
    /**
     * Converts an account request response into
     * an array of account DTOs to be
     * synchronized.
     *
     * @param array $responseBody
     * @return array
     */
    public function adapt(
        array $responseBody
    ): array;

    /**
     * Build the account DTOs.
     *
     * @return AccountResponseAdapterInterface
     */
    public function buildAccountDTOs(): AccountResponseAdapterInterface;
}
