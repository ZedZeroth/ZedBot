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
        $response = $adapter->adaptRequest(numberOfPayments: $numberOfPayments);
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

        /* Fetch bank account data */
        /*
        $apiURL = env('ZED_ENUMIS_BASE_URL') . env('ZED_ENUMIS_BENEFICIARIES_ENDPOINT');
        $postInput = [
            'accountERN' => env('ZED_ENUMIS_ACCOUNT_ERN'),
            'take' => 10
        ];
        $headers = [
            'Authorization' => 'Bearer ' . env('ZED_ENUMIS_ACCESS_KEY')
        ];

        $response = Http::withHeaders($headers)->post($apiURL, $postInput);
        $statusCode = $response->status();
        $responseBody = json_decode($response->getBody(), true);

        if ($statusCode == 200) {
            echo 'Success!';
        } else {
            echo 'Error code: ' . $statusCode;
        }
        */
    }
}
