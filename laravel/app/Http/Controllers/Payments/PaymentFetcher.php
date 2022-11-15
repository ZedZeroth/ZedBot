<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentFetcher
{
    /**
     * Fetches a new payment via external API
     * and returns it as an array of DTOs.
     *
     * @param PaymentAdapter $adapter
     * @param int $numberOfPayments
     * @return array
     */
    public function fetch($adapter, $numberOfPayments)
    {
        $response = $adapter
            ->adaptRequest(numberOfPayments: $numberOfPayments);

        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        if ($statusCode == 200) {
            return $adapter->adaptResponse(responseBody: $responseBody);
        } else {
            Log::error('Status code: ' . $statusCode);
            if (!empty($responseBody['responseStatus']['message'])) {
                Log::error($responseBody['responseStatus']['message']);
            }
            return [];
        }
    }
}
