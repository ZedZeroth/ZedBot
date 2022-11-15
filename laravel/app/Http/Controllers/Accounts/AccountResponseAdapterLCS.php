<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;

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

        /**
         * Extract relevant data from the response.
         *
         */
        $walletsWithBalance = [];
        /*💬*/ print_r($responseBody);
        foreach ($responseBody['currencies'] as $currencyCode => $currencyArray) {
            /*💬*/ //echo $currency . PHP_EOL;
            foreach ($currencyArray as $key => $value) {
                if (is_array($value)) {
                    if (array_key_exists('balance', $value)) {
                        if ($value['balance'] > 0) {
                            /*💬*/ echo $currencyCode . PHP_EOL;
                            /*💬*/ echo $key . PHP_EOL;
                            /*💬*/ echo 'Balance: ' . $value['balance'] . PHP_EOL . PHP_EOL;

                            if ($currencyCode == 'BTC') {
                                $network = 'Bitcoin';
                            } else if (
                                $currencyCode == 'ETH' OR
                                str_contains($currencyCode, 'ERC20')
                                ) {
                                $network = 'Ethereum';
                            } else if (
                                str_contains($currencyCode, 'BEP20')
                                ) {
                                $network = 'BSC';
                            } else {
                                $network = 'XXX';
                                log::warn("{$currencyCode} has no assigned network!");
                            }

                            /**
                             * Find currency and convert
                             * amount to base units.
                             *
                             */
                            $currency = Currency::
                            where(
                                'code',
                                $currencyCode
                            )->firstOrFail();

                            $multiplier = pow(
                                10,
                                $currency->decimalPlaces
                            );

                            $balance = $multiplier
                                * $value['balance'];

                            // ADAPT CURRENCY FOR SECOND BTC WALLET!

                            array_push(
                                $walletsWithBalance,
                                [
                                    'network' => $network,
                                    'address' => $key,
                                    'balance' => $balance,
                                    'currencyCode' => $currencyCode,
                                    'currency_id' => $currency->id,
                                ]
                            );
                        }
                    }
                }
            }
        }

        $DTOs = [];
        foreach ($walletsWithBalance as $wallet) {
            /**
             * Build the account DTO.
             *
            */
            array_push(
                $DTOs,
                new AccountDTO(
                    network: (string) $wallet['network'],
                    identifier: (string) 'fps'
                        . '::' . strtolower($wallet['currencyCode'])
                        . '::' . $wallet['address'],
                    customer_id: (int) 0,
                    networkAccountName: (string) '',
                    assumedAccountName: (string) 'LCS ' . $wallet['currencyCode'] . ' wallet',
                    currency_id: (int) $wallet['currency_id'],
                    balance: (int) $wallet['balance'],
                ),
            );
        }

        return $DTOs;
    }
}
