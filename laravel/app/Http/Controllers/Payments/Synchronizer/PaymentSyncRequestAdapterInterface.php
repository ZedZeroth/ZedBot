<?php

namespace App\Http\Controllers\Payments\Synchronizer;

interface PaymentSyncRequestAdapterInterface
{
    /**
     * Set the number of payments to fetch.
     *
     * @param int $numberOfPaymentsToFetch
     * @return PaymentSyncRequestAdapterInterface
     */
    public function setNumberOfPaymentsToFetch(
        int $numberOfPaymentsToFetch
    ): PaymentSyncRequestAdapterInterface;

    /**
     * Build the post parameters.
     *
     * @return PaymentSyncRequestAdapterInterface
     */
    public function buildPostParameters(): PaymentSyncRequestAdapterInterface;

    /**
     * Fetch the response.
     *
     * @return PaymentSyncRequestAdapterInterface
     */
    public function fetchResponse(): PaymentSyncRequestAdapterInterface;

    /**
     * Parse the response.
     *
     * @return PaymentSyncRequestAdapterInterface
     */
    public function parseResponse(): PaymentSyncRequestAdapterInterface;

    /**
     * Return the responseBody array.
     *
     * @return array
     */
    public function returnResponseBodyArray(): array;
}
