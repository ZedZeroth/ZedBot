<?php

namespace App\Http\Controllers\Payments;

interface PaymentRequestAdapterInterface
{
    /**
     * Requests payments from a provider
     * and return the response.
     *
     * @param int $numberOfPaymentsToFetch
     * @return array
     */
    public function request(
        int $numberOfPaymentsToFetch
    ): array;

    /**
     * Fetch the response.
     *
     * @return PaymentRequestAdapterInterface
     */
    public function fetchResponse(): PaymentRequestAdapterInterface;

    /**
     * Parse the response.
     *
     * @return PaymentRequestAdapterInterface
     */
    public function parseResponse(): PaymentRequestAdapterInterface;

    /**
     * Return the responseBody array.
     *
     * @return array
     */
    public function returnResponseBodyArray(): array;
}
