<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterENM;

class AccountRequestAdapterENM implements AccountRequestAdapterInterface
{
    /**
     * Requests beneficiary bank accounts from ENM.
     *
     * @param int $numberOfAccounts
     * @return array
     */
    public function request(
        int $numberOfAccounts
    ): array {
        $postParameters = [
            'accountERN' => env('ZED_ENM_ACCOUNT_ERN'),
            'take' => $numberOfAccounts
        ];

        $response = (new GeneralRequestAdapterENM())
            ->request(
                endpoint: env('ZED_ENM_BENEFICIARIES_ENDPOINT'),
                postParameters: $postParameters
            );

        $statusCode = $response->status();

        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        if ($statusCode == 200) {
            return (new AccountResponseAdapterENM())
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
