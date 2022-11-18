<?php

namespace App\Http\Controllers\Payments\Synchronizer;

interface PaymentSyncResponseAdapterInterface
{
     /**
     * Iterate through the payment data.
     * Build each account DTO and sync with accounts.
     * Build and return all payment DTOs.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOsSyncAccountsReturnPayments(
        array $responseBody
    ): array;
}
