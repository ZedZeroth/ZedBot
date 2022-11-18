<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

interface AccountsSynchronizerResponseAdapterInterface
{
    /**
     * Build the account DTOs.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOs(
        array $responseBody
    ): array;
}
