<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RequestAdapters\GetAdapterLCS;

class AccountRequestAdapterLCS implements AccountRequestAdapterInterface
{
    /**
     * Requests blockchain addresses from LCS.
     *
     * @param int $numberOfAccounts
     * @return array
     */
    public function request(
        int $numberOfAccounts
    ): array {
        $response = (new GetAdapterLCS())
            ->get(
                endpoint: env('ZED_LCS_WALLETS_ENDPOINT'),
            );

        $statusCode = $response->status();

        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        if ($statusCode == 200) {
            return (new AccountResponseAdapterLCS())
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
