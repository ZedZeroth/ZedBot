<?php

namespace App\Http\Controllers\Payments;

interface PaymentResponseAdapterInterface
{
    /**
     * Converts a request response into
     * an array of DTOs for the synchronizer.
     *
     * @param array $responseBody
     * @return array
     */
    public function respond(
        array $responseBody
    ): array;
}
