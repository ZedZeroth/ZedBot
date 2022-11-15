<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Models\Account;

class AccountResponseAdapterLCS implements AccountResponseAdapterInterface
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
        /*💬*/ print_r($responseBody);
        foreach ($responseBody['currencies'] as $currency => $currencyArray) {
            /*💬*/ //echo $currency . PHP_EOL;
            foreach ($currencyArray as $key => $value) {
                if (is_array($value)) {
                    if (array_key_exists('balance', $value)) {
                        if ($value['balance'] > 0) {
                            /*💬*/ echo $currency . PHP_EOL;
                            /*💬*/ echo $key . PHP_EOL;
                            /*💬*/ echo 'Balance: ' . $value['balance'] . PHP_EOL . PHP_EOL;
                        }
                    }
                }
            }

            /**
             * Build the account DTO.
             *
            */
            /*
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
            */
        }

        return $DTOs;
    }
}
