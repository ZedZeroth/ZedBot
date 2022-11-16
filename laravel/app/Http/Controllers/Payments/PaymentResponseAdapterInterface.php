<?php

namespace App\Http\Controllers\Payments;

interface PaymentResponseAdapterInterface
{
    /**
     * Converts a payment request response into
     * an array of account and payment DTOs to be
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
     * @return PaymentResponseAdapterInterface
     */
    public function buildAccountDTOs(): PaymentResponseAdapterInterface;

    /**
     * Sync the account DTOs.
     *
     * @return PaymentResponseAdapterInterface
     */
    public function syncAccountDTOs(): PaymentResponseAdapterInterface;

    /**
     * Build the payment DTOs.
     *
     * @return PaymentResponseAdapterInterface
     */
    public function buildPaymentDTOs(): PaymentResponseAdapterInterface;

    /**
     * Return the payment DTOs.
     *
     * @return array
     */
    public function returnPaymentDTOs(): array;
}
