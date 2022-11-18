<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

interface AccountSyncResponseAdapterInterface
{
    /**
     * Build the account DTOs.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildAccountDTOs(
        array $responseBody
    ): array;
}
