<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Models\Account;

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
            array_push(
                $DTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'fps'
                        . '::' . $result['sortCode']
                        . '::' . $result['accountNumber'],
                    customer_id: (int) 0,
                    networkAccountName: (string) '',
                    assumedAccountName: (string) $result['accountName']
                ),
            );
        }

        return $DTOs;
    }
}
