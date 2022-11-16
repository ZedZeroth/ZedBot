<?php

namespace App\Http\Controllers\Payments;

interface PaymentRequestAdapterInterface
{
    /**
     * Requests transacations (payments) from an API.
     *
     * @param int $numberOfPaymentsToFetch
     * @return array
     */
    public function request(int $numberOfPaymentsToFetch): array;
}
