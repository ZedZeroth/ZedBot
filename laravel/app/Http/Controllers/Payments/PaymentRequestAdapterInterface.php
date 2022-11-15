<?php

namespace App\Http\Controllers\Payments;

interface PaymentRequestAdapterInterface
{
    /**
     * Requests transacations (payments) from an API.
     *
     * @param int $numberOfPayments
     * @return array
     */
    public function request(
        int $numberOfPayments
    ): array;
}
