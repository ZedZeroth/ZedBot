<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;

class AccountResponseAdapterENM implements AccountResponseAdapterInterface
{
    /**
     * Converts an ENM account request response into
     * an array of account DTOs for the synchronizer.
     *
     * @param array $responseBody
     * @return array
     */
    public function respond(
        array $responseBody
    ): array {
        $DTOs = [];
        foreach ($responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            /**
             * Build the account DTO.
             *
            */

            try {
                $currency = Currency::where('code', 'GBP')->firstOrFail();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                Log::error(__METHOD__ . ' [' . __LINE__ . '] ' . $e->getMessage());
            }

            /*💬*/ //Log::warning($currency->id);

            array_push(
                $DTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'fps'
                        . '::' . 'gbp'
                        . '::' . $result['sortCode']
                        . '::' . $result['accountNumber'],
                    customer_id: (int) 0,
                    networkAccountName: (string) '',
                    label: (string) $result['accountName'],
                    currency_id: (int) $currency->id,
                    balance: (int) 0,
                ),
            );
        }

        return $DTOs;
    }
}
