<?php

namespace App\Http\Controllers\Payments;

interface PaymentResponseAdapterInterface
{
    /**
     * Converts a payment request response into
     * an array of payment DTOs for the synchronizer.
     *
     * @param array $responseBody
     * @return array
     */
    public function adapt(
        array $responseBody
    ): array;
}
