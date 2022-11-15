<?php

namespace App\Http\Controllers\Accounts;

interface AccountResponseAdapterInterface
{
    /**
     * Converts an account request response into
     * an array of account DTOs for the synchronizer.
     *
     * @param array $responseBody
     * @return array
     */
    public function respond(
        array $responseBody
    ): array;
}
