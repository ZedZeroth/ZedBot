<?php

namespace App\Http\Controllers\Payments\Synchronizer;

interface PaymentSyncResponseAdapterInterface
{
    /**
     * Set the response body.
     *
     * @param array $responseBody
     * @return PaymentSyncResponseAdapterInterface
     */
    public function setResponseBody(
        array $responseBody
    ): PaymentSyncResponseAdapterInterface;

    /**
     * Build the account DTOs.
     *
     * @return PaymentSyncResponseAdapterInterface
     */
    public function buildAndSyncAccountDTOs(): PaymentSyncResponseAdapterInterface;

    /**
     * Build the payment DTOs.
     *
     * @return array
     */
    public function buildPaymentDTOs(): array;
}
