<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MultiDomain\MoneyConverter;
use App\Models\Account;
use App\Models\Currency;
use App\Http\Controllers\Accounts\AccountDTO;

class AccountSyncResponseAdapterForLCS implements AccountSyncResponseAdapterInterface
{
    /**
     * Properties required by the adapter.
     *
     * @var array $responseBody
     * @var array $accountDTOs
     */
    private array $responseBody;
    private array $accountDTOs = [];

    /**
     * Sets the response body.
     *
     * @param array $responseBody
     * @return accountSyncResponseAdapterInterface
     */
    public function setResponseBody(
        array $responseBody
    ): accountSyncResponseAdapterInterface {
        $this->responseBody = $responseBody;
        return $this;
    }

    /**
     * Build the account DTOs.
     *
     * @return accountSyncResponseAdapterInterface
     */
    public function buildAccountDTOs(): accountSyncResponseAdapterInterface
    {
        //Extract relevant data from the response
        $walletsWithBalance = [];
        /*💬*/ //print_r($responseBody);
        foreach (
            $this->
                responseBody['currencies'] as $currencyCode => $currencyArray
        ) {
            /*💬*/ //echo $currency . PHP_EOL;
            foreach ($currencyArray as $address => $element) {
                if (is_array($element)) {
                    if (array_key_exists('balance', $element)) {
                        if ($element['balance'] > 0) {
                            /*💬*/ //echo $currencyCode . PHP_EOL;
                            /*💬*/ //echo $key . PHP_EOL;
                            /*💬*/ //echo 'Balance: ' . $element['balance'] . PHP_EOL . PHP_EOL;

                            if ($currencyCode == 'BTC') {
                                $network = 'Bitcoin';
                            } elseif (
                                $currencyCode == 'ETH' or
                                str_contains($currencyCode, 'ERC20')
                            ) {
                                $network = 'Ethereum';
                            } elseif (
                                str_contains($currencyCode, 'BEP20')
                            ) {
                                $network = 'BSC';
                            } else {
                                $network = 'XXX';
                                log::warn("{$currencyCode} has no assigned network!");
                            }

                            // Determine the currency
                            $currency = Currency::
                            where(
                                'code',
                                $currencyCode
                            )->firstOrFail();

                            // Convert amount to base units
                            $balance = (new MoneyConverter())
                            ->convert(
                                amount: $element['balance'],
                                currency: $currency
                            );

                            // ADAPT CURRENCY FOR SECOND BTC WALLET!

                            array_push(
                                $this->accountDTOs,
                                new AccountDTO(
                                    network: (string) $network,
                                    identifier: (string) 'fps'
                                        . '::' . strtolower($currencyCode)
                                        . '::' . $address,
                                    customer_id: (int) 0,
                                    networkAccountName: (string) '',
                                    label: (string) 'LCS ' . $currencyCode . ' wallet',
                                    currency_id: (int) $currency->id,
                                    balance: (int) $balance,
                                ),
                            );
                        }
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Return the account DTOs.
     *
     * @return array
     */
    public function returnAccountDTOs(): array
    {
        return $this->accountDTOs;
    }
}
