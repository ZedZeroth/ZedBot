<?php

namespace App\Http\Controllers\Payments;

interface PaymentRequestAdapterInterface
{
    /**
     * Requests transacations (payments) from an API.
     *
     * @return array
     */
    public function request(): array;
}
