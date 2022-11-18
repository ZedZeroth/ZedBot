<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use App\Models\Currency;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;

class AccountsSynchronizerResponseAdapterForENM implements
    AccountsSynchronizerResponseAdapterInterface,
    GeneralAdapterInterface
{
    /**
     * Build the account DTOs.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOs(
        array $responseBody
    ): array {
        $accountDTOs = [];
        foreach ($responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        'GBP'
                    )->firstOrFail();

            // Create the DTO
            array_push(
                $accountDTOs,
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

        return $accountDTOs;
    }
}
