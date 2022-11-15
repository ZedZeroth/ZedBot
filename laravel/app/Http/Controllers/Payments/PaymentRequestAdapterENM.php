<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RequestAdapters\PostAdapterENM;

class PaymentRequestAdapterENM implements PaymentRequestAdapterInterface
{
    /**
     * Requests transacations (payments) from ENM.
     *
     * @param int $numberOfPayments
     * @return array
     */
    public function request(
        int $numberOfPayments
    ): array {
        $postParameters = [
            'accountCode' => env('ZED_ENM_ACCOUNT_CODE'),
            'take' => $numberOfPayments
        ];

        $response = (new PostAdapterENM())
            ->post(
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $postParameters
            );

        $statusCode = $response->status();

        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        if ($statusCode == 200) {
            return (new PaymentResponseAdapterENM())
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
