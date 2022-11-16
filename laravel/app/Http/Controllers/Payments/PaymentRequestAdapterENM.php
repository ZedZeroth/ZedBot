<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentRequestAdapterENM implements PaymentRequestAdapterInterface
{
    /**
     * The PaymentRequestAdapter constructor.
     *
     * @param int $numberOfPaymentsToFetch
    */
    public function __construct(
        private int $numberOfPaymentsToFetch,
        private PostAdapterENM $postAdapter,
        private PaymentResponseAdapterENM $paymentResponseAdapter
    ) {
    }

    /**
     * Requests transacations (payments) from ENM.
     *
     * @return array
     */
    public function request(): array
    {
        // Build post parameters
        $postParameters = [
            'accountCode' => env('ZED_ENM_ACCOUNT_CODE'),
            'take' => $this->numberOfPaymentsToFetch
        ];

        // Fetch the response
        $response = ($this->postAdapter)
            ->post(
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $postParameters
            );

        // Parse the response
        $statusCode = $response->status();
        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        // Return the responseBody if successful
        if ($statusCode == 200) {
            return ($this->paymentResponseAdapter)
                ->respond(responseBody: $responseBody);
        } else {
            Log::error('Status code: ' . $statusCode);
            if (!empty($responseBody['responseStatus']['message'])) {
                Log::error($responseBody['responseStatus']['message']);
            }
            return [];
        }
    }
}
