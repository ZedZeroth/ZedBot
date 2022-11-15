<?php

namespace App\Http\Controllers\Payments;

interface PaymentAdapterInterface
{
    /**
     * Requests transacations (payments) from an API.
     *
     * @param int $numberOfPayments
     * @return Http
     */
    public function adaptRequest($numberOfPayments);

    /**
     * Converts a request response into
     * an array of DTOs for the controller.
     *
     * @param array $responseBody
     * @return array
     */
    public function adaptResponse($responseBody);
}
