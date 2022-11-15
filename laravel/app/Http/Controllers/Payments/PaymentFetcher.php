<?php

namespace App\Http\Controllers\Payments;

class PaymentFetcher
{
    /**
     * Fetches payments via an external API
     * and returns them as an array of DTOs.
     *
     * @param PaymentRequestAdapterInterface $requestAdapter
     * @param int $numberOfPayments
     * @return array
     */
    public function fetch(
        PaymentRequestAdapterInterface $requestAdapter,
        int $numberOfPayments
    ): array {
        return $requestAdapter->request(
            numberOfPayments: $numberOfPayments
        );
    }
}
